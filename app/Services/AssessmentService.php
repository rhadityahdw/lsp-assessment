<?php

namespace App\Services;

use App\Models\Assessment;
use App\Models\Scheme;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class AssessmentService
{
    /**
     * Get all assessments
     *
     * @return Collection<int, Assessment>
     */
    public function getAllAssessments(): Collection
    {
        return Assessment::with('schemes')->get();
    }

    /**
     * Get unit by ID with schemes
     * 
     * @param int $id
     * @return Assessment
     */
    public function getAssessmentById(int $id): Assessment
    {
        return Assessment::with('schemes')->findOrFail($id);
    }

    /**
     * Create a new assessment for a schedule with scheme
     *
     * @param array $assessmentData
     * @return Assessment
     */
    public function createAssessment(array $assessmentData): Assessment
    {
        return DB::transaction(function () use ($assessmentData) {
            $assessment = Assessment::create([
                'scheme_id' => $assessmentData['scheme_id'],
                'name' => $assessmentData['name'],
                'type' => $assessmentData['type'],
                'link' => $assessmentData['link'],
                'created_by' => auth()->user()->id,
            ]);

            return $assessment;
        });
    }

    /**
     * Update an existing assessment
     *
     * @param int $assessmentId
     * @param array $assessmentData
     * @return Assessment
     */
    public function updateAssessment(int $assessmentId, array $assessmentData): Assessment
    {
        return DB::transaction(function () use ($assessmentId, $assessmentData) {
            $assessment = Assessment::findOrFail($assessmentId);

            $assessment->update([
                'scheme_id' => $assessmentData['scheme_id'],
                'name' => $assessmentData['name'],
                'type' => $assessmentData['type'],
                'link' => $assessmentData['link'],
            ]);

            return $assessment;
        });
    }

    /**
     * Delete an assessment
     *
     * @param int $assessmentId
     * @return bool
     */
    public function deleteAssessment(int $assessmentId): bool
    {
        return DB::transaction(function () use ($assessmentId) {
            $assessment = Assessment::findOrFail($assessmentId);

            return $assessment->delete();
        });
    }
}