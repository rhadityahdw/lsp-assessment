<?php

namespace App\Services;

use App\Models\Certificate;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class CertificateService
{
    protected Certificate $certificate;

    public function __construct(Certificate $certificate)
    {
        $this->certificate = $certificate;
    }

    /**
     * Mengambil semua sertifikat dengan paginasi
     * 
     * @param int $perPage Jumlah item per halaman
     * @return LengthAwarePaginator
     */
    public function getAllCertificates(int $perPage = 10): LengthAwarePaginator
    {
        return $this->certificate->with(['user', 'scheme'])->paginate($perPage);
    }

     /**
     * Mengambil sertifikat berdasarkan ID.
     *
     * @param int $id ID sertifikat.
     * @return Certificate|null
     */
    public function getCertificateById(int $id): ?Certificate
    {
        return $this->certificate->with(['user', 'scheme'])->find($id);
    }

    /**
     * Mengambil sertifikat milik user tertentu.
     *
     * @param int|User $user ID user atau objek User.
     * @param int $perPage Jumlah item per halaman.
     * @return LengthAwarePaginator
     */
    public function getCertificatesForUser(int|User $user, int $perPage = 10): LengthAwarePaginator
    {
        $userId = $user instanceof User ? $user->id : $user;
        return $this->certificate->where('user_id', $userId)
                                 ->with(['scheme'])
                                 ->paginate($perPage);
    }

    /**
     * Membuat sertifikat baru dan menggunggah file.
     * 
     * @param array $data Data sertifikat yang akan dibuat.
     * @return Certificate
     * @throws \Exception
     */
    public function createCertificate(array $data): Certificate
    {
        DB::beginTransaction();

        try {
            if (isset($data['file_path']) && $data['file_path'] instanceof UploadedFile) {
                $data['file_path'] = $this->storeFileIfExists($data['file_path']);
            }

            unset($data['file']);
            $certificate = $this->certificate->create($data);

            DB::commit();
            return $certificate;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Gagal membuat sertifikat: " . $e->getMessage(), ['data' => $data]);
            throw new \Exception("Terjadi kesalahan saat membuat sertifikat: " . $e->getMessage());
        }
    }

    /**
     * Delete sertifikat
     * 
     * @param Certificate $certificate
     * @return void
     */
    public function deleteCertificate(Certificate $certificate)
    {
        DB::beginTransaction();

        try {
            if ($certificate->file_path && Storage::disk('public')->exists($certificate->file_path)) {
                Storage::disk('public')->delete($certificate->file_path);
                Log::info('File sertifikat dihapus dari storage: ' . $certificate->file_path);
            } else {
                Log::warning('File sertifikat tidak ditemukan di storage saat mencoba menghapus: ' . $certificate->file_path);
            }

            $result = $certificate->delete(); 

            DB::commit();

            Log::info('Sertifikat dihapus dari database: ' . $certificate->id);
            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Gagal menghapus sertifikat (ID: {$certificate->id}): " . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'certificate_id' => $certificate->id
            ]);
            throw new \Exception("Terjadi kesalahan saat menghapus sertifikat: " . $e->getMessage());
        }
    }

    /**
     * Memperbarui sertifikat yang ada.
     * 
     * @param Certificate $certificate Sertifikat yang akan diperbarui
     * @param array $data Data baru untuk sertifikat
     * @return Certificate
     * @throws \Exception
     */
    public function updateCertificate(Certificate $certificate, array $data): Certificate
    {
        DB::beginTransaction();

        try {
            // Handle file upload if new file is provided
            if (isset($data['file_path']) && $data['file_path'] instanceof UploadedFile) {
                // Delete old file if exists
                if ($certificate->file_path && Storage::disk('public')->exists($certificate->file_path)) {
                    Storage::disk('public')->delete($certificate->file_path);
                    Log::info('File sertifikat lama dihapus dari storage: ' . $certificate->file_path);
                }

                // Store new file
                $data['file_path'] = $this->storeFileIfExists($data['file_path']);
            }

            // Remove file key if exists to prevent errors
            unset($data['file']);

            // Update certificate
            $certificate->update($data);

            DB::commit();
            return $certificate->fresh();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Gagal memperbarui sertifikat (ID: {$certificate->id}): " . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'certificate_id' => $certificate->id,
                'data' => $data
            ]);
            throw new \Exception("Terjadi kesalahan saat memperbarui sertifikat: " . $e->getMessage());
        }
    }

    protected function storeFileIfExists(?UploadedFile $file): ?string
    {
        if ($file && $file->isValid()) {
            $userId = request()->input('user_id');
            $schemeId = request()->input('scheme_id');
            
            // Buat struktur direktori: files/certificates/{user_id}/{scheme_id}/
            $directory = "files/certificates/{$userId}/{$schemeId}";
            
            // Generate nama file unik
            $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
            
            // Simpan file dan return path relatif (tanpa 'public/')
            /** @var \Illuminate\Http\UploadedFile $file */
            $path = $file->storeAs($directory, $fileName, 'public');
            
            if (!$path) {
                throw new \Exception('Gagal menyimpan file sertifikat.');
            }
            
            return $path;
        }

        return null;
    }
}