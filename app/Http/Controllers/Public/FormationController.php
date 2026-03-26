<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Formation;
use App\Models\FormationCategory;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Inertia\Inertia;
use App\Services\GroqService;

class FormationController extends Controller
{
    public function __construct(private readonly GroqService $groqService)
    {}
    /**
     * Page catalogue — Bento Grid des domaines.
     */
    public function index(Request $request): \Inertia\Response
    {
        $categories = FormationCategory::ordered()
            ->withCount(['formations' => fn($q) => $q->published()])
            ->get()
            ->map(fn($c) => [
                'id'               => $c->id,
                'name'             => $c->name,
                'slug'             => $c->slug,
                'description'      => $c->description,
                'icon'             => $c->icon,
                'color'            => $c->color,
                'formations_count' => $c->formations_count,
            ]);

        // Si une catégorie est sélectionnée on charge ses formations
        $selectedCategory = null;
        $formations       = null;

        if ($request->filled('categorie')) {
            $cat = FormationCategory::where('slug', $request->categorie)->first();

            if ($cat) {
                $selectedCategory = [
                    'id'   => $cat->id,
                    'name' => $cat->name,
                    'slug' => $cat->slug,
                    'icon' => $cat->icon,
                ];

                $query = Formation::with('category')
                    ->published()
                    ->where('category_id', $cat->id)
                    ->ordered();

                if ($request->filled('level')) {
                    $query->byLevel($request->level);
                }

                if ($request->filled('search')) {
                    $term = $request->search;
                    $query->where(fn($q) => $q
                        ->where('title', 'like', "%{$term}%")
                        ->orWhere('short_description', 'like', "%{$term}%")
                    );
                }

                $formations = $query->paginate(9)->through(fn($f) => [
                    'id'                => $f->id,
                    'title'             => $f->title,
                    'slug'              => $f->slug,
                    'short_description' => $f->short_description,
                    'thumbnail_url'     => $f->thumbnail_url,
                    'level'             => $f->level,
                    'duration'          => $f->duration,
                    'current_price'     => $f->current_price,
                    'price'             => $f->price,
                    'price_promo'       => $f->price_promo,
                    'is_on_promo'       => $f->is_on_promo,
                    'has_certification' => $f->has_certification,
                    'average_rating'    => $f->average_rating,
                    'reviews_count'     => $f->approved_reviews_count,
                    'category'          => $f->category?->only(['name', 'slug', 'icon', 'color']),
                ]);
            }
        }

        return Inertia::render('Public/Formations', [
            'categories'       => $categories,
            'formations'       => $formations,
            'selectedCategory' => $selectedCategory,
            'filters'          => $request->only(['categorie', 'level', 'search']),
        ]);
    }

    /**
     * Fiche détaillée d'une formation.
     */
    public function show(string $slug): \Inertia\Response
    {
        $formation = Formation::with([
            'category',
            'author',
            'reviews'  => fn($q) => $q->approved()->latest()->limit(10),
            'sessions' => fn($q) => $q->published()->upcoming(),
        ])
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();

        $formation->incrementViewCount();

        $related = Formation::with('category')
            ->published()
            ->where('id', '!=', $formation->id)
            ->where('category_id', $formation->category_id)
            ->ordered()
            ->limit(3)
            ->get();

        return Inertia::render('Public/FormationShow', [
            'formation' => [
                'id'                 => $formation->id,
                'title'              => $formation->title,
                'slug'               => $formation->slug,
                'short_description'  => $formation->short_description,
                'content'            => $formation->content,
                'thumbnail_url'      => $formation->thumbnail_url,
                'banner_url'         => $formation->banner_url,
                'level'              => $formation->level,
                'duration'           => $formation->duration,
                'price'              => $formation->price,
                'price_promo'        => $formation->price_promo,
                'current_price'      => $formation->current_price,
                'is_on_promo'        => $formation->is_on_promo,
                'has_certification'  => $formation->has_certification,
                'objectives'         => $formation->objectives ?? [],
                'prerequisites'      => $formation->prerequisites ?? [],
                // ── Nouveaux champs ──
                'participant_profile' => $formation->participant_profile,
                'accessibility'       => $formation->accessibility,
                'access_delay'        => $formation->access_delay,
                'teaching_methods'    => $formation->teaching_methods,
                'teaching_resources'  => $formation->teaching_resources,
                'evaluation_methods'  => $formation->evaluation_methods,
                'success_rate'        => $formation->success_rate,
                'satisfaction_rate'   => $formation->satisfaction_rate,
                'employment_rate'     => $formation->employment_rate,
                'program'            => $formation->program ?? [],
                'average_rating'     => $formation->average_rating,
                'reviews_count'      => $formation->approved_reviews_count,
                'category'           => $formation->category?->only(['name', 'slug', 'icon', 'color']),
                'author'             => $formation->author?->only(['full_name', 'avatar_url']),
                'reviews'            => $formation->reviews->map(fn($r) => [
                    'reviewer_name'  => $r->reviewer_name,
                    'reviewer_job'   => $r->reviewer_job,
                    'reviewer_avatar'=> $r->reviewer_avatar,
                    'rating'         => $r->rating,
                    'comment'        => $r->comment,
                    'created_at'     => $r->created_at->translatedFormat('F Y'),
                ]),
                'sessions' => $formation->sessions->map(fn($s) => [
                    'id'                   => $s->id,
                    'label'                => $s->label,
                    'formatted_start_date' => $s->formatted_start_date,
                    'formatted_end_date'   => $s->formatted_end_date,
                    'schedule'             => $s->schedule,
                    'location'             => $s->location,
                    'modality'             => $s->modality,
                    'modality_label'       => $s->modality_label,
                    'modality_color'       => $s->modality_color,
                    'seats_total'          => $s->seats_total,
                    'seats_taken'          => $s->seats_taken,
                    'seats_available'      => $s->seats_available,
                    'is_full'              => $s->is_full,
                    'is_open'              => $s->is_open,
                    'price_override'       => $s->price_override,
                    'status'               => $s->status,
                ]),
            ],
            'relatedFormations' => $related->map(fn($f) => [
                'id'            => $f->id,
                'title'         => $f->title,
                'slug'          => $f->slug,
                'thumbnail_url' => $f->thumbnail_url,
                'level'         => $f->level,
                'current_price' => $f->current_price,
                'category'      => $f->category?->only(['name', 'slug']),
            ]),
        ]);
    }

    /**
     * Téléchargement PDF de la fiche formation.
     */
    public function downloadPdf(string $slug): Response
    {
        $formation = Formation::with([
            'category',
            'sessions' => fn($q) => $q->where('is_published', true)->orderBy('start_date'),
        ])
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();

        $pdf = Pdf::loadView('pdf.formation', compact('formation'))
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'defaultFont'          => 'DejaVu Sans',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled'      => false,
                'dpi'                  => 150,
            ]);

        return $pdf->download("fiche-formation-{$formation->slug}.pdf");
    }
}