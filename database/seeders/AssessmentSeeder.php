<?php

namespace Database\Seeders;

use App\Models\Assessment;
use App\Models\Scheme;
use App\Models\User;
use Illuminate\Database\Seeder;

class AssessmentSeeder extends Seeder
{
    public function run(): void
    {
        $asesors = User::role('asesor')->get();
        $schemes = Scheme::all();

        if ($asesors->isEmpty() || $schemes->isEmpty()) {
            return;
        }

        // Assessments for Junior Web Developer
        $jwdScheme = $schemes->where('code', 'JWD')->first();
        if ($jwdScheme) {
            Assessment::create([
                'scheme_id' => $jwdScheme->id,
                'name' => 'Asesmen Pemrograman Dasar',
                'type' => 'tulis',
                'link' => 'https://example.com/assessment/basic-programming',
                'created_by' => $asesors->random()->id,
            ]);

            Assessment::create([
                'scheme_id' => $jwdScheme->id,
                'name' => 'Proyek Website Sederhana',
                'type' => 'praktek',
                'link' => 'https://example.com/assessment/simple-website',
                'created_by' => $asesors->random()->id,
            ]);

            Assessment::create([
                'scheme_id' => $jwdScheme->id,
                'name' => 'Implementasi Git Flow',
                'type' => 'praktek',
                'link' => 'https://example.com/assessment/git-flow',
                'created_by' => $asesors->random()->id,
            ]);
        }

        // Assessments for Digital Marketing
        $dmScheme = $schemes->where('code', 'DM')->first();
        if ($dmScheme) {
            Assessment::create([
                'scheme_id' => $dmScheme->id,
                'name' => 'Analisis Pasar Digital',
                'type' => 'tulis',
                'link' => 'https://example.com/assessment/market-analysis',
                'created_by' => $asesors->random()->id,
            ]);

            Assessment::create([
                'scheme_id' => $dmScheme->id,
                'name' => 'Kampanye Media Sosial',
                'type' => 'praktek',
                'link' => 'https://example.com/assessment/social-media-campaign',
                'created_by' => $asesors->random()->id,
            ]);

            Assessment::create([
                'scheme_id' => $dmScheme->id,
                'name' => 'Optimasi SEO Website',
                'type' => 'praktek',
                'link' => 'https://example.com/assessment/seo-optimization',
                'created_by' => $asesors->random()->id,
            ]);
        }

        // Assessments for Android Developer
        $androidScheme = $schemes->where('code', 'AND')->first();
        if ($androidScheme) {
            Assessment::create([
                'scheme_id' => $androidScheme->id,
                'name' => 'Desain UI/UX Mobile',
                'type' => 'tulis',
                'link' => 'https://example.com/assessment/mobile-design',
                'created_by' => $asesors->random()->id,
            ]);

            Assessment::create([
                'scheme_id' => $androidScheme->id,
                'name' => 'Pengembangan Aplikasi Android',
                'type' => 'praktek',
                'link' => 'https://example.com/assessment/android-development',
                'created_by' => $asesors->random()->id,
            ]);

            Assessment::create([
                'scheme_id' => $androidScheme->id,
                'name' => 'Integrasi REST API',
                'type' => 'praktek',
                'link' => 'https://example.com/assessment/api-integration',
                'created_by' => $asesors->random()->id,
            ]);
        }

        // Assessments for Data Science
        $dsScheme = $schemes->where('code', 'DS')->first();
        if ($dsScheme) {
            Assessment::create([
                'scheme_id' => $dsScheme->id,
                'name' => 'Analisis Data dengan Python',
                'type' => 'tulis',
                'link' => 'https://example.com/assessment/python-analysis',
                'created_by' => $asesors->random()->id,
            ]);

            Assessment::create([
                'scheme_id' => $dsScheme->id,
                'name' => 'Visualisasi Data Interaktif',
                'type' => 'praktek',
                'link' => 'https://example.com/assessment/data-visualization',
                'created_by' => $asesors->random()->id,
            ]);

            Assessment::create([
                'scheme_id' => $dsScheme->id,
                'name' => 'Implementasi Machine Learning',
                'type' => 'praktek',
                'link' => 'https://example.com/assessment/machine-learning',
                'created_by' => $asesors->random()->id,
            ]);
        }
    }
}