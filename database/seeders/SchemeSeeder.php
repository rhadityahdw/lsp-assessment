<?php

namespace Database\Seeders;

use App\Models\PreAssessment;
use App\Models\Scheme;
use App\Models\Unit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SchemeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $scheme = Scheme::create([
            'code' => '001',
            'name' => 'Junior Web Developer',
            'type' => 'KKNI',
            'document_path' => 'documents/scheme_jwd.pdf',
            'summary' => 'Skema untuk pengembangan aplikasi web tingkat dasar.'
        ]);

        $units = [
            ['code' => 'UC01', 'name' => 'Menerapkan Prinsip Dasar Pemrograman'],
            ['code' => 'UC02', 'name' => 'Membangun Antarmuka Pengguna'],
            ['code' => 'UC03', 'name' => 'Membuat Kode Backend Sederhana'],
        ];

        foreach ($units as $unitData) {
            $unit = Unit::create($unitData);
            $scheme->units()->attach($unit->id);

            // Pre Assessment untuk setiap unit
            PreAssessment::create([
                'unit_id' => $unit->id,
                'question' => 'Apakah Anda memiliki pengalaman pada unit kompetensi: ' . $unit->name . '?'
            ]);
        }
    }
}
