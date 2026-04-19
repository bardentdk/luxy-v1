<?php

namespace App\Services\Commercial;

use App\Models\Quote;
use App\Models\QuoteItem;
use App\Models\EmailLog;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

class QuoteService
{
    public function create(array $data, array $items): Quote
    {
        $quote = Quote::create([
            'crm_contact_id'   => $data['crm_contact_id'],
            'deal_id'          => $data['deal_id'] ?? null,
            'created_by'       => auth()->id(),
            'reference'        => Quote::generateReference(),
            'status'           => 'draft',
            'issued_at'        => $data['issued_at'] ?? today(),
            'expires_at'       => $data['expires_at'] ?? today()->addDays(30),
            'discount_percent' => $data['discount_percent'] ?? 0,
            'notes'            => $data['notes'] ?? null,
            'terms'            => $data['terms'] ?? null,
        ]);

        $this->syncItems($quote, $items);
        $quote->recalculate();

        return $quote->fresh(['contact', 'items']);
    }

    public function update(Quote $quote, array $data, array $items): Quote
    {
        $quote->update([
            'deal_id'          => $data['deal_id'] ?? null,
            'issued_at'        => $data['issued_at'],
            'expires_at'       => $data['expires_at'],
            'discount_percent' => $data['discount_percent'] ?? 0,
            'notes'            => $data['notes'] ?? null,
            'terms'            => $data['terms'] ?? null,
        ]);

        $this->syncItems($quote, $items);
        $quote->recalculate();

        return $quote->fresh(['contact', 'items']);
    }

    /**
     * Envoi du devis par email via l'API Brevo (Http:: natif).
     */
    public function send(Quote $quote): void
    {
        $contact = $quote->contact;
        $html    = view('emails.quote', ['quote' => $quote->load('items')])->render();
        $apiKey  = config('services.brevo.api_key');

        // ── Envoi via Brevo API ───────────────────────────
        $response = Http::withHeaders([
            'api-key'      => $apiKey,
            'Content-Type' => 'application/json',
        ])->post('https://api.brevo.com/v3/smtp/email', [
            'sender'      => [
                'name'  => config('mail.from.name', 'Luxy Formation'),
                'email' => config('mail.from.address', 'contact@luxyformation.re'),
            ],
            'to'          => [[
                'name'  => $contact->full_name,
                'email' => $contact->email,
            ]],
            'subject'     => "Votre devis {$quote->reference} — Luxy Formation",
            'htmlContent' => $html,
        ]);

        // ── Log email ─────────────────────────────────────
        EmailLog::create([
            'crm_contact_id' => $contact->id,
            'quote_id'       => $quote->id,
            'to_email'       => $contact->email,
            'to_name'        => $contact->full_name,
            'subject'        => "Votre devis {$quote->reference} — Luxy Formation",
            'body_html'      => $html,
            'status'         => $response->successful() ? 'sent' : 'failed',
            'brevo_message_id' => $response->json('messageId') ?? null,
            'sent_at'        => now(),
        ]);

        if (! $response->successful()) {
            throw new \RuntimeException(
                'Erreur Brevo : ' . $response->body()
            );
        }

        $quote->update([
            'status'  => 'sent',
            'sent_at' => now(),
        ]);
    }

    public function downloadPdf(Quote $quote): Response
    {
        $pdf = Pdf::loadView('pdf.quote', [
            'quote' => $quote->load(['contact', 'items.product', 'createdBy']),
        ])
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'defaultFont'          => 'DejaVu Sans',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled'      => false,
                'dpi'                  => 150,
            ]);

        return $pdf->download("{$quote->reference}.pdf");
    }

    private function syncItems(Quote $quote, array $items): void
    {
        $quote->items()->delete();

        foreach ($items as $i => $item) {
            $qi = new QuoteItem([
                'product_id'       => $item['product_id'] ?? null,
                'name'             => $item['name'],
                'description'      => $item['description'] ?? null,
                'unit_price'       => $item['unit_price'],
                'quantity'         => $item['quantity'] ?? 1,
                'unit'             => $item['unit'] ?? 'formation',
                'tax_rate'         => $item['tax_rate'] ?? 0,
                'discount_percent' => $item['discount_percent'] ?? 0,
                'sort_order'       => $i,
            ]);
            $qi->total = $qi->calculateTotal();
            $quote->items()->save($qi);
        }
    }
}