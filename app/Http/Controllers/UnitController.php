<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\UnitService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UnitController extends Controller
{
    protected $unitService;

    public function __construct(UnitService $unitService)
    {
        $this->unitService = $unitService;
    }

    /**
     * Get all units
     * 
     * @return \Inertia\Response
     */
    public function index()
    {
        $units = $this->unitService->getAllUnits();

        return Inertia::render('units/Index', [
            'units' => $units
        ]);
    }

    /**
     * Show the form for creating a new unit
     * 
     * @return \Inertia\Response
     */
    public function create()
    {
        return Inertia::render('units/Create', [
            'units' => [],
            'pre_assessments' => [],
        ]);
    }

    /**
     * Store a newly created unit in storage
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'pre_assessments' => 'required|array|min:1',
            'pre_assessments.*.question' =>'required|string',
            'pre_assessments.*.expected_answer' =>'required|in:yes,no',
        ]);

        $unitData = [
            'code' => $validated['code'],
            'name' => $validated['name'],
        ];

        $this->unitService->createUnit($unitData, $validated['pre_assessments']);

        return redirect()->route('units.index')->with('success', 'Unit created successfully.');
    }

    /**
     * Display the specified unit
     *
     * @param  int  $id
     * @return \Inertia\Response
     */
    public function show($id)
    {
        $unit = $this->unitService->getUnitById($id);

        return Inertia::render('units/Show', [
            'unit' => $unit,
            'pre_assessments' => $unit->pre_assessments,
        ]);
    }

    /**
     * Show the form for editing the specified unit
     *
     * @param  int  $id
     * @return \Inertia\Response
     */
    public function edit($id)
    {
        $unit = $this->unitService->getUnitById($id);

        return Inertia::render('units/Edit', [
            'unit' => $unit
        ]);
    }

    /**
     * Update the specified unit in storage
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'code' =>'required|string|max:255',
            'name' =>'required|string|max:255',
            'pre_assessments' =>'required|array|min:1',
            'pre_assessments.*.id' =>'nullable|exists:pre_assessments,id',
            'pre_assessments.*.question' =>'required|string|max:255',
            'pre_assessments.*.expected_answer' =>'required|string|max:255',
        ]);

        $unitData = [
            'code' => $validated['code'],
            'name' => $validated['name'],
        ];

        $this->unitService->updateUnit($id, $unitData, $validated['pre_assessments']);

        return redirect()->route('units.index')->with('success', 'Unit updated successfully.');
    }

    /**
     * Remove the specified unit from storage
     * 
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $this->unitService->deleteUnit($id);

        return redirect()->route('units.index')->with('success', 'Unit deleted successfully.');
    }
}
