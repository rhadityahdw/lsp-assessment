<?php

use App\Models\Attempt;
use Illuminate\Database\Eloquent\Collection;
use App\Services;

class AttemptService
{

    public function getAllAttempts(): Collection
    {
        return Attempt::with(['users', 'schemes'])->get();
    }

    public function getAttemptById(int $id): Attempt
    {
        return Attempt::findOrFail($id);
    }
    
    /**
     * Buat attempt baru ketika user memilih scheme, mengupload dokumen, dan menjawab soal dari pre asesmen
     * 
     * @param User $user
     * @param ProfileData 
     *
     */
    public function createAttempt()
    {

    }
}