<?php

namespace App\Http\Controllers\Commercial;

use App\Http\Controllers\Controller;
use App\Models\CrmContact;
use App\Models\ContactForm;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CrmContactController extends Controller
{
    public function index(Request $request)
    {
        $contacts = CrmContact::with('assignedTo')
            ->when($request->search, fn($q, $s) => $q->search($s))
            ->when($request->status, fn($q, $s) => $q->byStatus($s))
            ->latest()
            ->paginate(20)
            ->through(fn($c) => [
                'id'               => $c->id,
                'full_name'        => $c->full_name,
                'email'            => $c->email,
                'phone'            => $c->phone,
                'company'          => $c->company,
                'status'           => $c->status,
                'status_label'     => $c->status_label,
                'status_color'     => $c->status_color,
                'initials'         => $c->initials,
                'source'           => $c->source,
                'deals_count'      => $c->deals()->count(),
                'last_contacted_at'=> $c->last_contacted_at?->diffForHumans(),
                'created_at'       => $c->created_at->format('d/m/Y'),
            ]);

        return Inertia::render('Commercial/Contacts/Index', [
            'contacts'       => $contacts,
            'filters'        => $request->only(['search', 'status']),
            'pendingLeads'   => ContactForm::whereNotExists(fn($q) =>
                $q->from('crm_contacts')->whereColumn('crm_contacts.contact_form_id', 'contact_forms.id')
            )->count(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Commercial/Contacts/Form');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name'  => ['required', 'string', 'max:100'],
            'email'      => ['required', 'email', 'unique:crm_contacts,email'],
            'phone'      => ['nullable', 'string', 'max:30'],
            'company'    => ['nullable', 'string', 'max:150'],
            'job_title'  => ['nullable', 'string', 'max:150'],
            'status'     => ['required', 'in:lead,prospect,client,lost'],
            'notes'      => ['nullable', 'string'],
        ]);

        $contact = CrmContact::create(array_merge($data, [
            'assigned_to' => auth()->id(),
            'source'      => 'manual',
        ]));

        return redirect()->route('commercial.contacts.show', $contact)
            ->with('success', 'Contact créé avec succès.');
    }

    public function show(CrmContact $contact)
    {
        $contact->load(['deals.stage', 'quotes', 'activities.user', 'emailLogs']);

        return Inertia::render('Commercial/Contacts/Show', [
            'contact' => [
                'id'               => $contact->id,
                'full_name'        => $contact->full_name,
                'first_name'       => $contact->first_name,
                'last_name'        => $contact->last_name,
                'email'            => $contact->email,
                'phone'            => $contact->phone,
                'company'          => $contact->company,
                'job_title'        => $contact->job_title,
                'status'           => $contact->status,
                'status_label'     => $contact->status_label,
                'status_color'     => $contact->status_color,
                'initials'         => $contact->initials,
                'source'           => $contact->source,
                'notes'            => $contact->notes,
                'tags'             => $contact->tags ?? [],
                'last_contacted_at'=> $contact->last_contacted_at?->format('d/m/Y'),
                'created_at'       => $contact->created_at->format('d/m/Y'),
                'deals'            => $contact->deals->map(fn($d) => [
                    'id'     => $d->id,
                    'title'  => $d->title,
                    'amount' => $d->amount,
                    'stage'  => $d->stage?->only(['name', 'color']),
                ]),
                'quotes'           => $contact->quotes->map(fn($q) => [
                    'id'          => $q->id,
                    'reference'   => $q->reference,
                    'total'       => $q->total,
                    'status'      => $q->status,
                    'status_label'=> $q->status_label,
                    'status_color'=> $q->status_color,
                    'issued_at'   => $q->issued_at->format('d/m/Y'),
                ]),
                'activities'       => $contact->activities->map(fn($a) => [
                    'id'           => $a->id,
                    'type'         => $a->type,
                    'type_color'   => $a->type_color,
                    'title'        => $a->title,
                    'body'         => $a->body,
                    'is_done'      => $a->is_done,
                    'scheduled_at' => $a->scheduled_at?->format('d/m/Y H:i'),
                    'created_at'   => $a->created_at->diffForHumans(),
                    'user_name'    => $a->user->full_name,
                ]),
                'email_logs_count' => $contact->emailLogs->count(),
            ],
        ]);
    }

    public function edit(CrmContact $contact)
    {
        return Inertia::render('Commercial/Contacts/Form', ['contact' => $contact]);
    }

    public function update(Request $request, CrmContact $contact)
    {
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name'  => ['required', 'string', 'max:100'],
            'email'      => ['required', 'email', 'unique:crm_contacts,email,' . $contact->id],
            'phone'      => ['nullable', 'string', 'max:30'],
            'company'    => ['nullable', 'string', 'max:150'],
            'job_title'  => ['nullable', 'string', 'max:150'],
            'status'     => ['required', 'in:lead,prospect,client,lost'],
            'notes'      => ['nullable', 'string'],
        ]);

        $contact->update($data);

        return back()->with('success', 'Contact mis à jour.');
    }

    public function destroy(CrmContact $contact)
    {
        $contact->delete();
        return redirect()->route('commercial.contacts.index')->with('success', 'Contact supprimé.');
    }

    /**
     * Importe les ContactForm non encore convertis en leads CRM.
     */
    public function importFromLeads()
    {
        $pending = ContactForm::whereNotExists(fn($q) =>
            $q->from('crm_contacts')->whereColumn('crm_contacts.contact_form_id', 'contact_forms.id')
        )->get();

        $imported = 0;
        foreach ($pending as $form) {
            $nameParts = explode(' ', $form->full_name, 2);
            CrmContact::create([
                'contact_form_id' => $form->id,
                'first_name'      => $nameParts[0],
                'last_name'       => $nameParts[1] ?? '',
                'email'           => $form->email,
                'phone'           => $form->phone,
                'source'          => 'contact_form',
                'status'          => 'lead',
                'notes'           => $form->message,
                'assigned_to'     => auth()->id(),
            ]);
            $imported++;
        }

        return back()->with('success', "{$imported} lead(s) importé(s) depuis les formulaires de contact.");
    }
}