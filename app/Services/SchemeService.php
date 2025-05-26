<?php

namespace App\Services;

use App\Models\Scheme;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SchemeService
{
    /**
     * Get all schemes
     *
     * @return Collection<int, Scheme>
     */
    public function getAllSchemes(): Collection
    {
        return Scheme::with('units')->get();
    }

    /**
     * Get scheme by ID with units
     * 
     * @param int $id
     * @return Scheme
     */
    public function getSchemeById(int $id): Scheme
    {
        return Scheme::with('units')->findOrFail($id);
    }

    /**
     * Create a new scheme
     * 
     * @param array $schemeData
     * @return Scheme
     */

    public function createScheme(array $schemeData, array $unitIds = []): Scheme
    {
        return DB::transaction(function () use ($schemeData, $unitIds) {
            Log::debug('Creating scheme with units:', ['unit_ids' => $unitIds]);

            $scheme = Scheme::create($schemeData);

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

    /**
     * Update a scheme
     * 
     * @param array $schemeData
     * @param array $unitIds
     * @return Scheme
     */
    public function updateScheme(int $id, array $schemeData, array $unitIds = []): Scheme
    {
        return DB::transaction(function () use ($id, $schemeData, $unitIds) {
            $scheme = Scheme::findOrFail($id);
            $scheme->update($schemeData);

            // Sync units
            if (!empty($unitIds)) {
                $existingUnitIds = Unit::whereIn('id', $unitIds)->pluck('id')->toArray();
                if (count($existingUnitIds) !== count($unitIds)) {
                    $invalidIds = array_diff($unitIds, $existingUnitIds);
                    throw new \InvalidArgumentException('Invalid unit IDs: ' . implode(', ', $invalidIds));
                }
                $scheme->units()->sync($existingUnitIds);
            } else {
                $scheme->units()->sync([]);
            }

            return $scheme->load('units');
        });
    }

    /**
     * Delete a scheme
     * 
     * @param int $id
     * @return bool
     */
    public function deleteScheme(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $scheme = Scheme::findOrFail($id);

            $scheme->units()->detach();

            return $scheme->delete();
        });
    }
}