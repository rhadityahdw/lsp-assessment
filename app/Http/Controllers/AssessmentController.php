<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Services\AssessmentService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AssessmentController extends Controller
{
    protected $assessmentService;

    public function __construct(AssessmentService $assessmentService)
    {
        $this->assessmentService = $assessmentService;
    }

    /**
     * Display a listing of the assessments
     * 
     * @return \Inertia\Response
     */
    public function index()
    {
        $assessments = Assessment::all();

        return Inertia::render('assessments/Index', [
            'assessments' => $assessments,
        ]);
    }

    /**
     * Show the form for creating a new assessment
     */
    public function create()
    {
        return Inertia::render('assessments/Create', [
            'assessments' => [], 
            'schemes' => []
        ]);
    }

    /**
     * Store a newly created assessment in storage
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'link' => 'required|string|max:255',
        ]);

        $assessmentData = [
            'name' => $validated['name'],
            'type' => $validated['type'],
            'link' => $validated['link']
        ];

        $this->assessmentService->createAssessment($assessmentData);

        return redirect()->route('assessments.index')
            ->with('message', 'Assessment berhasil dibuat');
    }

    /**
     * Display the specified assessment
     *
     * @param int $id
     * @return \Inertia\Response
     */
    public function show($id)
    {
        $assessment = $this->assessmentService->getAssessmentById($id);

        return Inertia::render('assessments/Show', [
            'assessment' => $assessment,
        ]);
    }

    /**
     * Show the form for editing the specified assessment
     * 
     * @param int $id
     * @return \Inertia\Response
     */
    public function edit($id)
    {
        $assessment = $this->assessmentService->getAssessmentById($id);

        return Inertia::render('assessments/Edit', [
            'assessment' => $assessment,
        ]);
    }

    /**
     * Update the specified assessment in storage
     *
     * @param \Illuminate\Http\Request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' =>'required|string|max:255',
            'type' =>'required|string|max:255', 
            'link' =>'required|string|max:255',
        ]);

        $assessmentData = [
            'name' => $validated['name'],
            'type' => $validated['type'],
            'link' => $validated['link']
        ];

        $this->assessmentService->updateAssessment($id, $assessmentData);

        return redirect()->route('assessments.index')
            ->with('message', 'Assessment berhasil diperbarui');
    }

    /**
     * Remove the specified assessment from storage
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $this->assessmentService->deleteAssessment($id);

        return redirect()->route('assessments.index')
            ->with('message', 'Assessment berhasil dihapus');
    }
}
