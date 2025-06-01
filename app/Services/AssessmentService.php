<?php

namespace App\Services;

use App\Models\Assessment;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class AssessmentService
{
    protected Assessment $assessment;

    public function __construct(Assessment $assessment)
    {
        $this->assessment = $assessment;
    }

    public function getAllAssessments(int $perPage = 10): LengthAwarePaginator
    {
        return $this->assessment->with(['scheme', 'createdBy'])->paginate($perPage);
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