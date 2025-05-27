<?php

namespace App\Services;

use App\Models\Attempt;
use App\Models\PreAssessmentAnswer;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;

class AttemptService
{
    /**
     * 
     */
    public function getAllAttempts(): Collection
    {
        return Attempt::with(['user:id,name', 'scheme:id,name'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($attempt) {
                return [
                    'id' => $attempt->id,
                    'user_name' => $attempt->user->name,
                    'scheme_name' => $attempt->scheme->name,
                    'status' => $attempt->status,
                    'created_at' => $attempt->created_at
                ];
            });
    }

    /**
     * Ambil data Attempt berdasarkan id lengkap dengan relasi
     */
    public function getAttemptById(int $id): Attempt
    {
        return Attempt::with([
            'user.profile',
            'scheme.units',
            'preAssessmentAnswers.preAssessment'
        ])->findOrFail($id);
    }

    /**
     * Update status Attempt
     */
    public function updateStatus(int $id, string $status): bool
    {
        $attempt = Attempt::findOrFail($id);
        $attempt->status = $status;
        return $attempt->save();
    }
    /**
     * Simpan data Attempt beserta jawaban pre-assessment
     * 
     */
    public function createAttempt(array $data, int $user_id): Attempt
    {
        return DB::transaction(function () use ($data, $user_id) {
            $attempt = Attempt::create([
                'user_id' => $user_id,
                'scheme_id' => $data['scheme_id'],
                'ktp' => $this->storeFileIfExists($data['ktp']),
                'ijazah' => $this->storeFileIfExists($data['ijazah']),
                'pas_foto' => $this->storeFileIfExists($data['pas_foto']),
                'bukti_kerja' => $this->storeFileIfExists($data['bukti_kerja'] ?? null),
                'portofolio' => $this->storeFileIfExists($data['portofolio'] ?? null),
                'status' => 'submitted',
            ]);

            foreach ($data['pre_assessment_answers'] as $answer) {
                PreAssessmentAnswer::create([
                    'attempt_id' => $attempt->id,
                    'pre_assessment_id' => $answer['id'],
                    'answer' => $answer['answer'],
                ]);
            };

            return $attempt;
        });
    }

    protected function storeFileIfExists(?UploadedFile $file): ?string
    {
        if ($file && $file->isValid()) {
            $path = $file->store('public/files');
            return str_replace('public/', '', $path);
        }

        return null;
    }
}