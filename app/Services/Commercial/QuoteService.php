<?php

namespace App\Services\Commercial;

use App\Models\Quote;
use App\Models\QuoteItem;
use App\Models\CrmContact;
use App\Services\BrevoService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class QuoteService
{
    public function __construct(private readonly BrevoService $brevoService)
    {}

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

    public function send(Quote $quote): void
    {
        $contact = $quote->contact;

        $this->brevoService->sendTransactional(
            to:      $contact->email,
            toName:  $contact->full_name,
            subject: "Votre devis {$quote->reference} — Luxy Formation",
            html:    view('emails.quote', ['quote' => $quote])->render(),
        );

        $quote->update([
            'status'  => 'sent',
            'sent_at' => now(),
        ]);
    }

    public function downloadPdf(Quote $quote): Response
    {
        $pdf = Pdf::loadView('pdf.quote', ['quote' => $quote->load(['contact', 'items.product', 'createdBy'])])
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