<?php

namespace App\Services;

use App\Models\PreAssessment;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class UnitService
{
    /**
     * Get all units
     * 
     * @return Collection<int, Unit>
     */
    public function getAllUnits(): Collection
    {
        return Unit::with('schemes', 'preAssessments')->get();
    }

    /**
     * Get unit by ID with schemes
     * 
     * @param int $id
     * @return Unit
     */
    public function getUnitById(int $id): Unit
    {
        return Unit::with(['schemes', 'preAssessments'])->findOrFail($id);
    }

    /**
     * Create a new unit
     * 
     * @param array $unitData
     * @param array $pre_assessments
     * @return Unit
     */
    public function createUnit(array $unitData, array $pre_assessments = []): Unit
    {
        return DB::transaction(function () use ($unitData, $pre_assessments) {
            $unit = Unit::create([
                'code' => $unitData['code'],
                'name' => $unitData['name'],
            ]);

            if (!empty($pre_assessments)) {
                $this->createPreAssessments($unit, $pre_assessments);
            }

            return $unit->load('pre_assessments');
        });
    }


    /**
     * Update a unit
     *
     * @param int $id
     * @param array $unitData
     * @param array $pre_assessments
     * @return Unit
     */
    public function updateUnit(int $id, array $unitData, array $pre_assessments = []): Unit
    {
        return DB::transaction(function () use ($id, $unitData, $pre_assessments) {
            $unit = Unit::findOrFail($id);
            $unit->update($unitData);

            if (!empty($pre_assessments)) {
                // Hapus hanya pre-assesments yang tidak ada di input baru
                $existingIds = collect($pre_assessments)
                    ->pluck('id')
                    ->filter()
                    ->toArray();

                $unit->pre_assessments()
                    ->whereNotIn('id', $existingIds)
                    ->delete();

                // Update atau create yang baru
                foreach ($pre_assessments as $preAssessment) {
                    if (isset($preAssessment['id'])) {
                        // Update existing
                        PreAssessment::where('id', $preAssessment['id'])
                            ->update($preAssessment);
                    } else {
                        // Create new
                        $unit->pre_assessments()->create($preAssessment);
                    }
                }
                $this->createPreAssessments($unit, $pre_assessments);
            }

            return $unit->load('pre_asssessments');
        });
    }

    /**
     * Delete a unit
     * 
     * @param int $id
     * @return bool
     */
    public function deleteUnit(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $unit = Unit::findOrFail($id);

            // Hapus assessment
            $unit->pre_assessments()->delete();

            return $unit->delete();
        });
    }

    /**
     * Create pre-assessments for a unit
     * 
     * @param Unit $unit
     * @param array $pre_assessments
     * @throws InvalidArgumentException
     */
    protected function createPreAssessments(Unit $unit, array $pre_assessments): void
    {
        $records = array_map(function ($pre_assessment) use ($unit) {
            // Validasi struktur data
            if (empty($pre_assessment['question']) || empty($pre_assessment['expected_answer'])) {
                throw new InvalidArgumentException('Invalid pre-assessment data');
            }

            return [
                'unit_id' => $unit->id,
                'question' => $pre_assessment['question'],
                'expected_answer' => $pre_assessment['expected_answer'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }, $pre_assessments);

        PreAssessment::insert($records);
    }
}