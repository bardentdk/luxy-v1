<?php

namespace App\Http\Controllers\Commercial;

use App\Http\Controllers\Controller;
use App\Models\CrmContact;
use App\Models\Deal;
use App\Models\DealStage;
use App\Models\Quote;
use App\Models\EmailLog;
use App\Models\DealActivity;
use Inertia\Inertia;

class CommercialDashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        // ── KPIs ───────────────────────────────────────────
        $wonStage  = DealStage::where('is_won', true)->first();
        $lostStage = DealStage::where('is_lost', true)->first();

        $totalDeals     = Deal::whereNull('deleted_at')->count();
        $dealsWon       = $wonStage  ? Deal::where('deal_stage_id', $wonStage->id)->count()  : 0;
        $dealsLost      = $lostStage ? Deal::where('deal_stage_id', $lostStage->id)->count() : 0;
        $conversionRate = $totalDeals > 0 ? round(($dealsWon / $totalDeals) * 100, 1) : 0;

        $revenueWon      = $wonStage ? Deal::where('deal_stage_id', $wonStage->id)->sum('amount') : 0;
        $revenuePipeline = Deal::whereDoesntHave('stage', fn($q) => $q->where('is_won', true)->orWhere('is_lost', true))->sum('amount');

        $totalContacts  = CrmContact::whereNull('deleted_at')->count();
        $newLeadsThisMonth = CrmContact::whereMonth('created_at', now()->month)->count();

        $quotesSent     = Quote::where('status', 'sent')->count();
        $quotesAccepted = Quote::where('status', 'accepted')->count();

        // ── Pipeline par étape ─────────────────────────────
        $pipeline = DealStage::ordered()
            ->withCount(['deals as deals_count' => fn($q) => $q->whereNull('deleted_at')])
            ->withSum(['deals as deals_amount' => fn($q) => $q->whereNull('deleted_at')], 'amount')
            ->get()
            ->map(fn($s) => [
                'id'          => $s->id,
                'name'        => $s->name,
                'color'       => $s->color,
                'is_won'      => $s->is_won,
                'is_lost'     => $s->is_lost,
                'deals_count' => $s->deals_count,
                'deals_amount'=> $s->deals_amount ?? 0,
            ]);

        // ── Activités à faire ──────────────────────────────
        $todoActivities = DealActivity::with(['deal', 'contact'])
            ->where('is_done', false)
            ->whereNotNull('scheduled_at')
            ->orderBy('scheduled_at')
            ->limit(10)
            ->get()
            ->map(fn($a) => [
                'id'           => $a->id,
                'type'         => $a->type,
                'title'        => $a->title,
                'scheduled_at' => $a->scheduled_at?->format('d/m/Y H:i'),
                'deal_title'   => $a->deal?->title,
                'contact_name' => $a->contact?->full_name,
            ]);

        // ── Derniers contacts ──────────────────────────────
        $recentContacts = CrmContact::latest()
            ->limit(5)
            ->get()
            ->map(fn($c) => [
                'id'         => $c->id,
                'full_name'  => $c->full_name,
                'email'      => $c->email,
                'company'    => $c->company,
                'status'     => $c->status,
                'status_label'=> $c->status_label,
                'status_color'=> $c->status_color,
                'initials'   => $c->initials,
                'created_at' => $c->created_at->diffForHumans(),
            ]);

        // ── Devis récents ──────────────────────────────────
        $recentQuotes = Quote::with('contact')
            ->latest()
            ->limit(5)
            ->get()
            ->map(fn($q) => [
                'id'           => $q->id,
                'reference'    => $q->reference,
                'contact_name' => $q->contact->full_name,
                'total'        => $q->total,
                'status'       => $q->status,
                'status_label' => $q->status_label,
                'status_color' => $q->status_color,
                'issued_at'    => $q->issued_at->format('d/m/Y'),
            ]);

        return Inertia::render('Commercial/Dashboard', [
            'kpis' => [
                'revenue_won'      => $revenueWon,
                'revenue_pipeline' => $revenuePipeline,
                'total_contacts'   => $totalContacts,
                'new_leads_month'  => $newLeadsThisMonth,
                'conversion_rate'  => $conversionRate,
                'quotes_sent'      => $quotesSent,
                'quotes_accepted'  => $quotesAccepted,
                'deals_won'        => $dealsWon,
                'deals_in_progress'=> $totalDeals - $dealsWon - $dealsLost,
            ],
            'pipeline'       => $pipeline,
            'todoActivities' => $todoActivities,
            'recentContacts' => $recentContacts,
            'recentQuotes'   => $recentQuotes,
        ]);
    }
}