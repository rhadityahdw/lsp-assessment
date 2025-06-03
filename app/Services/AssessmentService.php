<?php

namespace App\Services;

use App\Models\Assessment;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AssessmentService
{
    protected Assessment $assessment;

    public function __construct(Assessment $assessment)
    {
        $this->assessment = $assessment;
    }

    public function getAllAssessments(int $perPage = 10): LengthAwarePaginator
    {
        $query = $this->assessment->with(['scheme', 'createdBy']);
        
        if (Auth::user()->hasRole('asesor')) {
            $query->where('created_by', Auth::id());
        }
        
        return $query->paginate($perPage);
    }

    public function getAssessmentById(int $id): ?Assessment
    {
        return $this->assessment->with(['scheme', 'createdBy'])->find($id);
    }

    public function getAssessmentsByAsesor(int $id, int $perPage = 10): LengthAwarePaginator
    {
        return $this->assessment->where('created_by', $id)
                                ->with(['scheme', 'createdBy'])
                                ->paginate($perPage);
    }

    public function createAssessment(array $data): Assessment
    {
        if (!Auth::user()->can('create assessment')) {
            throw new \Exception('Unauthorized action.');
        }

        DB::beginTransaction();

        try {
            $assessment = $this->assessment->create($data);

            DB::commit();
            return $assessment;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception("Terjadi kesalahan saat membuat asesmen: " . $e->getMessage());
        }
    }

    public function updateAssessment(Assessment $assessment, array $data): Assessment
    {
        if (!Auth::user()->can('edit assessment')) {
            throw new \Exception('Unauthorized action.');
        }

        // Only allow asesor to edit their own assessments
        if (Auth::user()->hasRole('asesor') && $assessment->created_by !== Auth::id()) {
            throw new \Exception('You can only edit your own assessments.');
        }

        DB::beginTransaction();

        try {
            $assessment->update($data);

            DB::commit();
            return $assessment;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception("Terjadi kesalahan saat memperbarui asesmen: ". $e->getMessage());
        }
    }

    public function deleteAssessment(Assessment $assessment): bool
    {
        if (!Auth::user()->can('delete assessment')) {
            throw new \Exception('Unauthorized action.');
        }

        // Only allow asesor to delete their own assessments
        if (Auth::user()->hasRole('asesor') && $assessment->created_by !== Auth::id()) {
            throw new \Exception('You can only delete your own assessments.');
        }

        DB::beginTransaction();

        try {
            $assessment->delete();

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception("Terjadi kesalahan saat menghapus asesmen: ". $e->getMessage());
        }
    }
}