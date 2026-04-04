<?php

namespace App\Services\Commercial;

use App\Models\Deal;
use App\Models\DealStage;
use App\Models\DealActivity;

class PipelineService
{
    /**
     * Retourne le pipeline complet avec deals par étape.
     */
    public function getPipelineData(): array
    {
        $stages = DealStage::ordered()
            ->with(['deals' => fn($q) => $q
                ->with(['contact', 'assignedTo'])
                ->whereNull('deleted_at')
                ->orderByDesc('updated_at')
            ])
            ->get();

        return $stages->map(fn($stage) => [
            'id'       => $stage->id,
            'name'     => $stage->name,
            'slug'     => $stage->slug,
            'color'    => $stage->color,
            'is_won'   => $stage->is_won,
            'is_lost'  => $stage->is_lost,
            'deals'    => $stage->deals->map(fn($d) => $this->formatDeal($d)),
            'total'    => $stage->deals->sum('amount'),
            'count'    => $stage->deals->count(),
        ])->toArray();
    }

    public function moveDeal(Deal $deal, int $stageId): Deal
    {
        $stage = DealStage::findOrFail($stageId);
        $deal->moveToStage($stage);

        DealActivity::create([
            'deal_id'        => $deal->id,
            'crm_contact_id' => $deal->crm_contact_id,
            'user_id'        => auth()->id(),
            'type'           => 'note',
            'title'          => "Deal déplacé vers « {$stage->name} »",
            'is_done'        => true,
            'done_at'        => now(),
        ]);

        return $deal->fresh('stage');
    }

    public function formatDeal(Deal $deal): array
    {
        return [
            'id'                  => $deal->id,
            'title'               => $deal->title,
            'amount'              => $deal->amount,
            'probability'         => $deal->probability,
            'expected_close_date' => $deal->expected_close_date?->format('d/m/Y'),
            'contact'             => $deal->contact ? [
                'id'        => $deal->contact->id,
                'full_name' => $deal->contact->full_name,
                'email'     => $deal->contact->email,
                'initials'  => $deal->contact->initials,
            ] : null,
            'assigned_to' => $deal->assignedTo ? [
                'id'       => $deal->assignedTo->id,
                'initials' => $deal->assignedTo->initials,
            ] : null,
        ];
    }
}