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
            $filePath = null;

            if (isset($data['file']) && $data['file'] instanceof UploadedFile) {
                $file = $data['file'];

                /** @var \Illuminate\Http\UploadedFile $file */
                $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
                $filePath = $file->storeAs('certificates', $fileName, 'public');

                if (!$filePath) {
                    throw new \Exception('Gagal mengunggah file sertifikat.');
                }
                $data['file_path'] = $filePath;
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
     * Memperbarui data sertifikat dan/atau file.
     *
     * @param Certificate $certificate Objek sertifikat yang akan diperbarui.
     * @param array $data Data yang akan diperbarui.
     * @return Certificate
     * @throws \Exception Jika ada masalah dalam proses update atau upload.
     */
    public function updateCertificate(Certificate $certificate, array $data): Certificate
    {
        DB::beginTransaction();

        try {
            if (isset($data['file']) && $data['file'] instanceof UploadedFile) {

                if ($certificate->file_path && Storage::disk('public')->exists($certificate->file_path)) {
                    Storage::disk('public')->delete($certificate->file_path);
                }

                $file = $data['file'];

                /** @var \Illuminate\Http\UploadedFile $file */
                $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
                $filePath = $file->storeAs('certificates', $fileName, 'public');

                if (!$filePath) {
                    throw new \Exception("Gagal mengunggah file sertifikat baru.");
                }
                $data['file_path'] = $filePath;
            } elseif (array_key_exists('file', $data) && $data['file'] === null) {
                // Jika 'file' diset null, berarti user ingin menghapus file yang sudah ada
                if ($certificate->file_path && Storage::disk('public')->exists($certificate->file_path)) {
                    Storage::disk('public')->delete($certificate->file_path);
                }
                $data['file_path'] = null;
            }

            unset($data['file']);

            $certificate->update($data);

            DB::commit();
            return $certificate;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Gagal memperbarui sertifikat (ID: {$certificate->id}): " . $e->getMessage(), ['data' => $data]);
            throw new \Exception("Terjadi kesalahan saat memperbarui sertifikat: " . $e->getMessage());
        }
    }

    /**
     * Menghapus sertifikat dan file terkaitnya.
     *
     * @param Certificate $certificate Objek sertifikat yang akan dihapus.
     * @return bool
     * @throws \Exception Jika ada masalah dalam proses penghapusan.
     */
    public function deleteCertificate(Certificate $certificate): bool
    {
        DB::beginTransaction();

        try {
            // Hapus file dari penyimpanan jika ada
            if ($certificate->file_path && Storage::disk('public')->exists($certificate->file_path)) {
                Storage::disk('public')->delete($certificate->file_path);
            }

            $result = $certificate->delete();

            DB::commit();
            return $result;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Gagal menghapus sertifikat (ID: {$certificate->id}): " . $e->getMessage());
            throw new \Exception("Terjadi kesalahan saat menghapus sertifikat: " . $e->getMessage());
        }
    }

    /**
     * Mendapatkan URL publik untuk file sertifikat.
     *
     * @param Certificate $certificate
     * @return string|null
     */
    public function getCertificateFileUrl(Certificate $certificate): ?string
    {
        if ($certificate->file_path) {
        return Storage::url($certificate->file_path);
        }
        return null;
    }

}