<?php

namespace App\Services;

use App\Exceptions\InvalidUnitsException;
use App\Models\Scheme;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SchemeService
{
    public function getAllSchemes(): Collection
    {
        return Scheme::with('units')->get();
    }

    public function getSchemeById(int $id): Scheme
    {
        return Scheme::with('units')->findOrFail($id);
    }

    public function createScheme(array $data, array $unitIds): Scheme
    {
        return DB::transaction(function () use ($data, $unitIds) {
            $scheme = Scheme::create($data);

            if (!empty($unitIds)) {
                $existingUnitIds = Unit::whereIn('id', $unitIds)->pluck('id')->toArray();
                if (count($existingUnitIds) !== count($unitIds)) {
                    throw new \Exception('Some units do not exist');
                }
                $scheme->units()->attach($existingUnitIds);
            }

            return $scheme->load('units');
        });
    }

    public function updateScheme(int $id, array $data, array $unitIds): Scheme
    {
        return DB::transaction(function () use ($id, $data, $unitIds) {
            $scheme = Scheme::findOrFail($id);
            $scheme->update($data);
            $this->validateAndSyncUnits($scheme, $unitIds);
            
            Log::info('Scheme updated', [
                'scheme_id' => $id,
                'changes' => $data
            ]);
            
            return $scheme->load('units');
        });
    }

    public function deleteScheme(int $id): void
    {
        DB::transaction(function () use ($id) {
            $scheme = Scheme::findOrFail($id);
            $scheme->units()->detach();
            $scheme->delete();
            
            Log::info('Scheme deleted', ['scheme_id' => $id]);
        });
    }

    /**
     * Ambil semua skema lengkap dengan unit dan pre asesmen-nya
     */
    public function getSchemesWithUnitsAndPreAssessments()
    {
        return Scheme::with('units.preAssessments')->get();
    }

    protected function validateAndAttachUnits(Scheme $scheme, array $unitIds): void
    {
        $existingUnitIds = Unit::whereIn('id', $unitIds)->pluck('id');
        
        if ($existingUnitIds->count() !== count($unitIds)) {
            throw new InvalidUnitsException('Invalid unit IDs provided');
        }
        
        $scheme->units()->attach($existingUnitIds);
    }

    protected function validateAndSyncUnits(Scheme $scheme, array $unitIds): void
    {
        $existingUnitIds = Unit::whereIn('id', $unitIds)->pluck('id');
        
        if ($existingUnitIds->count() !== count($unitIds)) {
            throw new InvalidUnitsException('Invalid unit IDs provided');
        }
        
        $scheme->units()->sync($existingUnitIds);
    }
}