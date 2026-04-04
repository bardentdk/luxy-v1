<?php

namespace App\Services\Commercial;

use App\Models\CrmContact;
use App\Models\EmailSequence;
use App\Models\EmailLog;
use App\Services\BrevoService;
use Carbon\Carbon;

class SequenceService
{
    public function __construct(private readonly BrevoService $brevoService)
    {}

    /**
     * Déclenche une séquence pour un contact.
     * Les emails sont programmés selon delay_days de chaque étape.
     */
    public function trigger(EmailSequence $sequence, CrmContact $contact): void
    {
        foreach ($sequence->steps->where('is_active', true) as $step) {
            $sendAt = Carbon::now()->addDays($step->delay_days);

            // Pour J+0, on envoie immédiatement
            if ($step->delay_days === 0) {
                $this->sendStep($step, $contact);
            } else {
                // Enregistrer l'email en attente — à traiter via un job/scheduler
                EmailLog::create([
                    'crm_contact_id'   => $contact->id,
                    'sequence_step_id' => $step->id,
                    'to_email'         => $contact->email,
                    'to_name'          => $contact->full_name,
                    'subject'          => $step->subject,
                    'body_html'        => $this->renderTemplate($step->body_html, $contact),
                    'status'           => 'pending',
                    'sent_at'          => $sendAt,
                ]);
            }
        }
    }

    public function sendStep(object $step, CrmContact $contact): void
    {
        $html = $this->renderTemplate($step->body_html, $contact);

        try {
            $this->brevoService->sendTransactional(
                to:      $contact->email,
                toName:  $contact->full_name,
                subject: $step->subject,
                html:    $html,
            );

            EmailLog::create([
                'crm_contact_id'   => $contact->id,
                'sequence_step_id' => $step->id,
                'to_email'         => $contact->email,
                'to_name'          => $contact->full_name,
                'subject'          => $step->subject,
                'body_html'        => $html,
                'status'           => 'sent',
                'sent_at'          => now(),
            ]);

            $contact->update(['last_contacted_at' => now()]);

        } catch (\Exception $e) {
            EmailLog::create([
                'crm_contact_id'   => $contact->id,
                'sequence_step_id' => $step->id,
                'to_email'         => $contact->email,
                'to_name'          => $contact->full_name,
                'subject'          => $step->subject,
                'status'           => 'failed',
            ]);
        }
    }

    /**
     * Remplace les variables dans le template email.
     * Ex: {{contact.first_name}}, {{contact.email}}
     */
    private function renderTemplate(string $html, CrmContact $contact): string
    {
        $replacements = [
            '{{contact.first_name}}' => $contact->first_name,
            '{{contact.last_name}}'  => $contact->last_name,
            '{{contact.full_name}}'  => $contact->full_name,
            '{{contact.email}}'      => $contact->email,
            '{{contact.company}}'    => $contact->company ?? '',
        ];

        return str_replace(
            array_keys($replacements),
            array_values($replacements),
            $html
        );
    }
}