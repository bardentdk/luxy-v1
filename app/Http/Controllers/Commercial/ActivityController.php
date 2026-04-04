<?php

namespace App\Http\Controllers\Commercial;

use App\Http\Controllers\Controller;
use App\Models\DealActivity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'deal_id'        => ['nullable', 'exists:deals,id'],
            'crm_contact_id' => ['nullable', 'exists:crm_contacts,id'],
            'type'           => ['required', 'in:note,call,email,meeting,task'],
            'title'          => ['required', 'string', 'max:255'],
            'body'           => ['nullable', 'string'],
            'scheduled_at'   => ['nullable', 'date'],
        ]);

        DealActivity::create(array_merge($data, ['user_id' => auth()->id()]));

        return back()->with('success', 'Activité ajoutée.');
    }

    public function markDone(DealActivity $activity)
    {
        $activity->update(['is_done' => true, 'done_at' => now()]);
        return back()->with('success', 'Activité marquée comme faite.');
    }

    public function destroy(DealActivity $activity)
    {
        $activity->delete();
        return back()->with('success', 'Activité supprimée.');
    }
}