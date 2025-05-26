<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\AssessmentRequest;
use App\Models\Assessment;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class AssessmentController extends Controller
{
    public function index()
    {
        $assessments = Assessment::with('schemes')->get();

        return Inertia::render('assessments/Index', [
            'assessments' => $assessments,
        ]);
    }

    public function create()
    {
        return Inertia::render('assessments/Create', [
            'assessments' => [], 
            'schemes' => []
        ]);
    }

    public function store(AssessmentRequest $request)
    {
        DB::transaction(function () use ($request) {
            Assessment::create([
                'scheme_id' => $request->scheme_id,
                'name' => $request->name,
                'type' => $request->type,
                'link' => $request->link,
                'created_by' => auth()->user->id,
            ]);
        });

        return redirect()->route('assessments.index')
            ->with('success', 'Assessment created successfully');
    }

    public function edit(Assessment $assessment)
    {
        return Inertia::render('assessments/Edit', [
            'assessment' => $assessment->load('schemes'),
        ]);
    }

    public function update(AssessmentRequest $request, Assessment $assessment)
    {
        DB::transaction(function () use ($request, $assessment) {
            $assessment->update([
                'scheme_id' => $request->scheme_id,
                'name' => $request->name,
                'type' => $request->type,
                'link' => $request->link,
            ]);
        });

        return redirect()->route('assessments.index')
            ->with('success', 'Assessment updated successfully');
    }

    public function destroy(Assessment $assessment)
    {
        DB::transaction(function () use ($assessment) {
            $assessment->delete();
        });

        return redirect()->route('assessments.index')
            ->with('success', 'Assessment deleted successfully');
    }
}
