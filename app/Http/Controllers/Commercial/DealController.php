<?php

namespace App\Http\Controllers\Commercial;

use App\Http\Controllers\Controller;
use App\Models\Deal;
use App\Models\DealStage;
use App\Models\DealActivity;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DealController extends Controller
{
    public function index(Request $request)
    {
        $deals = Deal::with(['contact', 'stage', 'assignedTo'])
            ->when($request->search, fn($q, $s) =>
                $q->where('title', 'like', "%{$s}%")
            )
            ->latest()
            ->paginate(20)
            ->through(fn($d) => [
                'id'           => $d->id,
                'title'        => $d->title,
                'amount'       => $d->amount,
                'probability'  => $d->probability,
                'contact_name' => $d->contact->full_name,
                'stage'        => $d->stage->only(['name', 'color', 'is_won', 'is_lost']),
                'created_at'   => $d->created_at->format('d/m/Y'),
            ]);

        return Inertia::render('Commercial/Deals/Index', compact('deals'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'crm_contact_id'      => ['required', 'exists:crm_contacts,id'],
            'deal_stage_id'       => ['required', 'exists:deal_stages,id'],
            'title'               => ['required', 'string', 'max:255'],
            'amount'              => ['nullable', 'numeric', 'min:0'],
            'expected_close_date' => ['nullable', 'date'],
            'probability'         => ['nullable', 'integer', 'min:0', 'max:100'],
            'formation_id'        => ['nullable', 'exists:formations,id'],
            'notes'               => ['nullable', 'string'],
        ]);

        $deal = Deal::create(array_merge($data, ['assigned_to' => auth()->id()]));

        DealActivity::create([
            'deal_id'        => $deal->id,
            'crm_contact_id' => $deal->crm_contact_id,
            'user_id'        => auth()->id(),
            'type'           => 'note',
            'title'          => 'Deal créé',
            'is_done'        => true,
            'done_at'        => now(),
        ]);

        return back()->with('success', 'Opportunité créée.');
    }

    public function show(Deal $deal)
    {
        $deal->load(['contact', 'stage', 'assignedTo', 'formation', 'activities.user', 'quotes']);

        return Inertia::render('Commercial/Deals/Show', [
            'deal'   => [
                'id'                  => $deal->id,
                'title'               => $deal->title,
                'amount'              => $deal->amount,
                'probability'         => $deal->probability,
                'expected_close_date' => $deal->expected_close_date?->format('Y-m-d'),
                'notes'               => $deal->notes,
                'contact'             => [
                    'id'        => $deal->contact->id,
                    'full_name' => $deal->contact->full_name,
                    'email'     => $deal->contact->email,
                    'initials'  => $deal->contact->initials,
                ],
                'stage'      => $deal->stage->only(['id', 'name', 'color', 'is_won', 'is_lost']),
                'formation'  => $deal->formation?->only(['id', 'title', 'price']),
                'activities' => $deal->activities->map(fn($a) => [
                    'id'         => $a->id,
                    'type'       => $a->type,
                    'type_color' => $a->type_color,
                    'title'      => $a->title,
                    'body'       => $a->body,
                    'is_done'    => $a->is_done,
                    'created_at' => $a->created_at->diffForHumans(),
                    'user_name'  => $a->user->full_name,
                    'user_initials' => $a->user->initials,
                ]),
                'quotes'  => $deal->quotes->map(fn($q) => [
                    'id'          => $q->id,
                    'reference'   => $q->reference,
                    'total'       => $q->total,
                    'status'      => $q->status,
                    'status_label'=> $q->status_label,
                    'status_color'=> $q->status_color,
                ]),
            ],
            'stages' => DealStage::ordered()->get(['id', 'name', 'color', 'is_won', 'is_lost']),
        ]);
    }

    public function update(Request $request, Deal $deal)
    {
        $data = $request->validate([
            'deal_stage_id'       => ['sometimes', 'exists:deal_stages,id'],
            'title'               => ['required', 'string', 'max:255'],
            'amount'              => ['nullable', 'numeric', 'min:0'],
            'probability'         => ['nullable', 'integer', 'min:0', 'max:100'],
            'expected_close_date' => ['nullable', 'date'],
            'lost_reason'         => ['nullable', 'string'],
            'notes'               => ['nullable', 'string'],
        ]);

        $deal->update($data);
        return back()->with('success', 'Opportunité mise à jour.');
    }

    public function destroy(Deal $deal)
    {
        $deal->delete();
        return redirect()->route('commercial.pipeline')->with('success', 'Deal supprimé.');
    }
}