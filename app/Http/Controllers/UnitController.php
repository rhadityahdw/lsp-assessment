<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\UnitRequest;
use App\Services\UnitService;
use App\Models\Unit;
use App\Models\PreAssessment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class UnitController extends Controller
{
    public function __construct(
        protected UnitService $unitService
    ) {
        $this->middleware('permission:view unit')->only(['index', 'show']);
        $this->middleware('permission:create unit')->only(['create', 'store']);
        $this->middleware('permission:edit unit')->only(['edit', 'update']);
        $this->middleware('permission:delete unit')->only(['destroy']);
    }

    public function index()
    {
        $units = Unit::with(['schemes', 'preAssessments'])->get();

        return Inertia::render('units/Index', [
            'units' => $units,
            'permissions' => [
                'create' => Auth::user()->can('create unit'),
                'edit' => Auth::user()->can('edit unit'),
                'delete' => Auth::user()->can('delete unit'),
                'view' => Auth::user()->can('view unit'),
            ]
        ]);
    }

    public function create()
    {
        return Inertia::render('units/Create', [
            'units' => [],
            'pre_assessments' => [],
        ]);
    }

    public function store(UnitRequest $request)
    {
        DB::transaction(function () use ($request) {
            $unit = Unit::create([
                'code' => $request->code,
                'name' => $request->name,
            ]);

            foreach ($request->pre_assessments as $assessment) {
                PreAssessment::create([
                    'unit_id' => $unit->id,
                    'question' => $assessment['question'],
                ]);
            }
        });

        return redirect()->route('units.index')
            ->with('success', 'Unit created successfully');
    }

    public function show($id)
    {
        $unit = $this->unitService->getUnitById($id);

        return Inertia::render('units/Show', [
            'unit' => $unit,
            'pre_assessments' => $unit->pre_assessments,
        ]);
    }

    public function edit(Unit $unit)
    {
        return Inertia::render('units/Edit', [
            'unit' => $unit->load(['schemes', 'preAssessments']),
        ]);
    }

    public function update(UnitRequest $request, Unit $unit)
    {
        DB::transaction(function () use ($request, $unit) {
            $unit->update([
                'code' => $request->code,
                'name' => $request->name,
            ]);

            // Delete existing pre-assessments
            $unit->preAssessments()->delete();

            // Create new pre-assessments
            foreach ($request->pre_assessments as $assessment) {
                PreAssessment::create([
                    'unit_id' => $unit->id,
                    'question' => $assessment['question'],
                    'expected_answer' => $assessment['expected_answer'],
                ]);
            }
        });

        return redirect()->route('units.index')
            ->with('success', 'Unit updated successfully');
    }

    public function destroy(Unit $unit)
    {
        DB::transaction(function () use ($unit) {
            $unit->preAssessments()->delete();
            $unit->schemes()->detach();
            $unit->delete();
        });

        return redirect()->route('units.index')
            ->with('success', 'Unit deleted successfully');
    }
}
