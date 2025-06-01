<?php

namespace Database\Seeders;

use App\Models\PreAssessment;
use App\Models\Scheme;
use App\Models\Unit;
use Illuminate\Database\Seeder;

class SchemeSeeder extends Seeder
{
    public function run(): void
    {
        $schemes = [
            [
                'code' => 'JWD',
                'name' => 'Junior Web Developer',
                'type' => 'KKNI',
                'document_path' => 'documents/scheme_jwd.pdf',
                'summary' => 'Skema untuk pengembangan aplikasi web tingkat dasar.',
                'units' => [
                    ['code' => 'JWD.01', 'name' => 'Menerapkan Prinsip Dasar Pemrograman'],
                    ['code' => 'JWD.02', 'name' => 'Membangun Antarmuka Pengguna'],
                    ['code' => 'JWD.03', 'name' => 'Membuat Kode Backend Sederhana'],
                    ['code' => 'JWD.04', 'name' => 'Menggunakan Version Control System'],
                ]
            ],
            [
                'code' => 'DM',
                'name' => 'Digital Marketing',
                'type' => 'Industri',
                'document_path' => 'documents/scheme_dm.pdf',
                'summary' => 'Skema untuk pemasaran digital dan sosial media.',
                'units' => [
                    ['code' => 'DM.01', 'name' => 'Menganalisis Pasar Digital'],
                    ['code' => 'DM.02', 'name' => 'Mengelola Media Sosial'],
                    ['code' => 'DM.03', 'name' => 'Membuat Konten Digital'],
                    ['code' => 'DM.04', 'name' => 'Mengoptimasi SEO'],
                ]
            ],
            [
                'code' => 'AND',
                'name' => 'Android Developer',
                'type' => 'KKNI',
                'document_path' => 'documents/scheme_android.pdf',
                'summary' => 'Skema untuk pengembangan aplikasi Android.',
                'units' => [
                    ['code' => 'AND.01', 'name' => 'Merancang UI/UX Mobile'],
                    ['code' => 'AND.02', 'name' => 'Membuat Layout Android'],
                    ['code' => 'AND.03', 'name' => 'Mengelola Data Lokal'],
                    ['code' => 'AND.04', 'name' => 'Mengintegrasikan API'],
                ]
            ],
            [
                'code' => 'DS',
                'name' => 'Data Science',
                'type' => 'Industri',
                'document_path' => 'documents/scheme_ds.pdf',
                'summary' => 'Skema untuk analisis dan pengolahan data.',
                'units' => [
                    ['code' => 'DS.01', 'name' => 'Mengolah Data dengan Python'],
                    ['code' => 'DS.02', 'name' => 'Analisis Data Statistik'],
                    ['code' => 'DS.03', 'name' => 'Visualisasi Data'],
                    ['code' => 'DS.04', 'name' => 'Machine Learning Dasar'],
                ]
            ],
        ];

        foreach ($schemes as $schemeData) {
            $units = $schemeData['units'];
            unset($schemeData['units']);
            
            $scheme = Scheme::create($schemeData);

            foreach ($units as $unitData) {
                $unit = Unit::create([
                    'code' => $unitData['code'],
                    'name' => $unitData['name']
                ]);

                $scheme->units()->attach($unit->id);

                // Create multiple pre-assessments for each unit
                $preAssessments = [
                    [
                        'question' => "Apakah Anda memiliki pengalaman dalam {$unitData['name']}?",
                    ],
                    [
                        'question' => "Apakah Anda dapat mendemonstrasikan {$unitData['name']}?",
                    ],
                    [
                        'question' => "Apakah Anda memiliki sertifikat terkait {$unitData['name']}?",
                    ]
                ];

                foreach ($preAssessments as $paData) {
                    PreAssessment::create([
                        'unit_id' => $unit->id,
                        'question' => $paData['question'],
                    ]);
                }
            }
        }
    }
}
