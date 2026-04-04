<?php

namespace App\Http\Controllers\Commercial;

use App\Http\Controllers\Controller;
use App\Models\EmailSequence;
use App\Models\EmailSequenceStep;
use App\Models\CrmContact;
use App\Services\Commercial\SequenceService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EmailSequenceController extends Controller
{
    public function __construct(private readonly SequenceService $sequenceService)
    {}

    public function index()
    {
        $sequences = EmailSequence::withCount('steps')
            ->latest()
            ->get()
            ->map(fn($s) => [
                'id'          => $s->id,
                'name'        => $s->name,
                'trigger'     => $s->trigger,
                'is_active'   => $s->is_active,
                'steps_count' => $s->steps_count,
            ]);

        return Inertia::render('Commercial/Sequences/Index', compact('sequences'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'              => ['required', 'string', 'max:255'],
            'trigger'           => ['required', 'in:deal_created,quote_sent,no_activity,stage_changed'],
            'target_stage_slug' => ['nullable', 'string'],
            'is_active'         => ['boolean'],
        ]);

        EmailSequence::create(array_merge($data, ['created_by' => auth()->id()]));
        return back()->with('success', 'Séquence créée.');
    }

    public function show(EmailSequence $sequence)
    {
        $sequence->load('steps');

        return Inertia::render('Commercial/Sequences/Show', [
            'sequence' => [
                'id'       => $sequence->id,
                'name'     => $sequence->name,
                'trigger'  => $sequence->trigger,
                'is_active'=> $sequence->is_active,
                'steps'    => $sequence->steps->map(fn($s) => [
                    'id'          => $s->id,
                    'delay_days'  => $s->delay_days,
                    'subject'     => $s->subject,
                    'body_html'   => $s->body_html,
                    'sort_order'  => $s->sort_order,
                    'is_active'   => $s->is_active,
                    'logs_count'  => $s->logs()->count(),
                ]),
            ],
            'contacts' => CrmContact::orderBy('first_name')->get(['id', 'first_name', 'last_name', 'email']),
        ]);
    }

    public function update(Request $request, EmailSequence $sequence)
    {
        $data = $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'is_active' => ['boolean'],
        ]);
        $sequence->update($data);
        return back()->with('success', 'Séquence mise à jour.');
    }

    public function destroy(EmailSequence $sequence)
    {
        $sequence->delete();
        return redirect()->route('commercial.sequences.index')->with('success', 'Séquence supprimée.');
    }

    public function addStep(Request $request, EmailSequence $sequence)
    {
        $data = $request->validate([
            'delay_days' => ['required', 'integer', 'min:0'],
            'subject'    => ['required', 'string', 'max:255'],
            'body_html'  => ['required', 'string'],
        ]);

        $sequence->steps()->create(array_merge($data, [
            'sort_order' => $sequence->steps()->count(),
        ]));

        return back()->with('success', 'Étape ajoutée.');
    }

    public function updateStep(Request $request, EmailSequence $sequence, EmailSequenceStep $step)
    {
        $data = $request->validate([
            'delay_days' => ['required', 'integer', 'min:0'],
            'subject'    => ['required', 'string', 'max:255'],
            'body_html'  => ['required', 'string'],
            'is_active'  => ['boolean'],
        ]);
        $step->update($data);
        return back()->with('success', 'Étape mise à jour.');
    }

    public function destroyStep(EmailSequence $sequence, EmailSequenceStep $step)
    {
        $step->delete();
        return back()->with('success', 'Étape supprimée.');
    }

    public function trigger(Request $request, EmailSequence $sequence, CrmContact $contact)
    {
        $this->sequenceService->trigger($sequence, $contact);
        return back()->with('success', "Séquence déclenchée pour {$contact->full_name}.");
    }
}