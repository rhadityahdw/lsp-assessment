<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\AssessmentRequest;
use App\Http\Resources\AssessmentResource;
use App\Http\Resources\SchemeResource;
use App\Models\Assessment;
use App\Models\Scheme;
use App\Services\AssessmentService;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AssessmentController extends Controller
{
    protected AssessmentService $assessmentService;

    public function __construct(AssessmentService $assessmentService)
    {
        $this->assessmentService = $assessmentService;
    }

    public function index()
    {
        $assessments = $this->assessmentService->getAllAssessments(10);

        return Inertia::render('assessments/Index', [
            'assessments' => AssessmentResource::collection($assessments),
        ]);
    }

    public function create()
    {
        $schemes = SchemeResource::collection(Scheme::orderBy('name')->get());

        return Inertia::render('assessments/Create', [
            'schemes' => $schemes,
        ]);
    }

    public function store(AssessmentRequest $request)
    {
        try {
            $data = array_merge($request->validated(), [
                'created_by' => Auth::id(),
            ]);

            $this->assessmentService->createAssessment($data);

            return redirect()->route('assessments.index')
                ->with('success', 'Assessment created successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }

    public function show(int $id)
    {
        $assessment = $this->assessmentService->getAssessmentById($id);

        if (!$assessment) {
            return redirect()->route('assessments.index')
                ->with('error', 'Assessment not found');
        }

        return Inertia::render('assessments/Show', [
            'assessment' => $assessment
        ]);
    }

    public function edit(Assessment $assessment)
    {
        return Inertia::render('assessments/Edit', [
            'assessment' => $assessment->load('scheme'),
        ]);
    }

    public function update(AssessmentRequest $request, Assessment $assessment)
    {
        try {
            $this->assessmentService->updateAssessment($assessment, $request->validated());

            return redirect()->route('assessments.index')
                ->with('success', 'Assessment updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }

    public function destroy(Assessment $assessment)
    {
        try {
            $this->assessmentService->deleteAssessment($assessment);

            return redirect()->route('assessments.index')
                ->with('success', 'Assessment deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }

    public function asesorAssessments()
    {
        $asesorId = Auth::id();
        $assessments = $this->assessmentService->getAssessmentsByAsesor($asesorId);

        return Inertia::render('assessments/AsesorAssessments', [
            'assessments' => $assessments
        ]);
    }
}
