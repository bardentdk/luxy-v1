<?php

namespace App\Http\Controllers\Commercial;

use App\Http\Controllers\Controller;
use App\Models\Deal;
use App\Models\DealStage;
use App\Models\CrmContact;
use App\Models\Formation;
use App\Services\Commercial\PipelineService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PipelineController extends Controller
{
    public function __construct(private readonly PipelineService $pipelineService)
    {}

    public function index()
    {
        return Inertia::render('Commercial/Pipeline', [
            'pipeline' => $this->pipelineService->getPipelineData(),
            'stages'   => DealStage::ordered()->get(['id', 'name', 'slug', 'color', 'is_won', 'is_lost']),
            'contacts' => CrmContact::orderBy('first_name')->get(['id', 'first_name', 'last_name', 'email']),
            'formations'=> Formation::published()->ordered()->get(['id', 'title', 'price']),
        ]);
    }

    public function move(Request $request, Deal $deal)
    {
        $request->validate(['stage_id' => ['required', 'integer', 'exists:deal_stages,id']]);

        $this->pipelineService->moveDeal($deal, $request->stage_id);

        return back()->with('success', 'Deal déplacé avec succès.');
    }
}