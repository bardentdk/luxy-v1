<?php

namespace App\Http\Controllers\Commercial;

use App\Http\Controllers\Controller;
use App\Models\Quote;
use App\Models\CrmContact;
use App\Models\Deal;
use App\Models\CommercialProduct;
use App\Services\Commercial\QuoteService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class QuoteController extends Controller
{
    public function __construct(private readonly QuoteService $quoteService)
    {}

    public function index(Request $request)
    {
        $quotes = Quote::with(['contact', 'createdBy'])
            ->when($request->status, fn($q, $s) => $q->where('status', $s))
            ->latest()
            ->paginate(20)
            ->through(fn($q) => [
                'id'           => $q->id,
                'reference'    => $q->reference,
                'contact_name' => $q->contact->full_name,
                'total'        => $q->total,
                'status'       => $q->status,
                'status_label' => $q->status_label,
                'status_color' => $q->status_color,
                'issued_at'    => $q->issued_at->format('d/m/Y'),
                'expires_at'   => $q->expires_at->format('d/m/Y'),
            ]);

        return Inertia::render('Commercial/Quotes/Index', [
            'quotes'  => $quotes,
            'filters' => $request->only(['status']),
        ]);
    }

    public function create(Request $request)
    {
        return Inertia::render('Commercial/Quotes/Form', [
            'contacts' => CrmContact::orderBy('first_name')->get(['id', 'first_name', 'last_name', 'email', 'company']),
            'deals'    => Deal::with('contact')->latest()->get(['id', 'title', 'crm_contact_id']),
            'products' => CommercialProduct::active()->get(['id', 'name', 'reference', 'unit_price', 'unit', 'tax_rate']),
            'preselect_contact' => $request->contact_id,
            'preselect_deal'    => $request->deal_id,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'crm_contact_id'   => ['required', 'exists:crm_contacts,id'],
            'deal_id'          => ['nullable', 'exists:deals,id'],
            'issued_at'        => ['required', 'date'],
            'expires_at'       => ['required', 'date', 'after:issued_at'],
            'discount_percent' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'notes'            => ['nullable', 'string'],
            'terms'            => ['nullable', 'string'],
            'items'            => ['required', 'array', 'min:1'],
            'items.*.name'     => ['required', 'string'],
            'items.*.unit_price'=> ['required', 'numeric', 'min:0'],
            'items.*.quantity' => ['required', 'numeric', 'min:0.01'],
            'items.*.tax_rate' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ]);

        $quote = $this->quoteService->create($data, $data['items']);

        return redirect()->route('commercial.quotes.show', $quote)
            ->with('success', "Devis {$quote->reference} créé.");
    }

    public function show(Quote $quote)
    {
        $quote->load(['contact', 'deal', 'items.product', 'createdBy']);

        return Inertia::render('Commercial/Quotes/Show', [
            'quote' => [
                'id'               => $quote->id,
                'reference'        => $quote->reference,
                'status'           => $quote->status,
                'status_label'     => $quote->status_label,
                'status_color'     => $quote->status_color,
                'issued_at'        => $quote->issued_at->format('d/m/Y'),
                'expires_at'       => $quote->expires_at->format('d/m/Y'),
                'subtotal'         => $quote->subtotal,
                'tax_total'        => $quote->tax_total,
                'total'            => $quote->total,
                'discount_percent' => $quote->discount_percent,
                'notes'            => $quote->notes,
                'terms'            => $quote->terms,
                'sent_at'          => $quote->sent_at?->format('d/m/Y H:i'),
                'accepted_at'      => $quote->accepted_at?->format('d/m/Y H:i'),
                'contact'          => $quote->contact->only(['id', 'full_name', 'email', 'company', 'phone']),
                'deal'             => $quote->deal?->only(['id', 'title']),
                'created_by'       => $quote->createdBy->full_name,
                'items'            => $quote->items->map(fn($i) => [
                    'id'               => $i->id,
                    'name'             => $i->name,
                    'description'      => $i->description,
                    'unit_price'       => $i->unit_price,
                    'quantity'         => $i->quantity,
                    'unit'             => $i->unit,
                    'tax_rate'         => $i->tax_rate,
                    'discount_percent' => $i->discount_percent,
                    'total'            => $i->total,
                ]),
            ],
        ]);
    }

    public function edit(Quote $quote)
    {
        $quote->load('items.product');

        return Inertia::render('Commercial/Quotes/Form', [
            'quote'    => $quote,
            'contacts' => CrmContact::orderBy('first_name')->get(['id', 'first_name', 'last_name', 'email', 'company']),
            'deals'    => Deal::latest()->get(['id', 'title', 'crm_contact_id']),
            'products' => CommercialProduct::active()->get(['id', 'name', 'reference', 'unit_price', 'unit', 'tax_rate']),
        ]);
    }

    public function update(Request $request, Quote $quote)
    {
        $data = $request->validate([
            'deal_id'          => ['nullable', 'exists:deals,id'],
            'issued_at'        => ['required', 'date'],
            'expires_at'       => ['required', 'date'],
            'discount_percent' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'notes'            => ['nullable', 'string'],
            'terms'            => ['nullable', 'string'],
            'items'            => ['required', 'array', 'min:1'],
            'items.*.name'     => ['required', 'string'],
            'items.*.unit_price'=> ['required', 'numeric', 'min:0'],
            'items.*.quantity' => ['required', 'numeric', 'min:0.01'],
        ]);

        $this->quoteService->update($quote, $data, $data['items']);
        return back()->with('success', 'Devis mis à jour.');
    }

    public function send(Quote $quote)
    {
        $this->quoteService->send($quote);
        return back()->with('success', 'Devis envoyé par email.');
    }

    public function updateStatus(Request $request, Quote $quote)
    {
        $request->validate(['status' => ['required', 'in:draft,sent,accepted,refused,expired']]);
        $quote->update(['status' => $request->status]);
        return back()->with('success', 'Statut mis à jour.');
    }

    public function downloadPdf(Quote $quote)
    {
        return $this->quoteService->downloadPdf($quote);
    }

    public function destroy(Quote $quote)
    {
        $quote->delete();
        return redirect()->route('commercial.quotes.index')->with('success', 'Devis supprimé.');
    }
}