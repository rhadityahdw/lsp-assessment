<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\CertificateResource;
use App\Models\Certificate;
use App\Services\CertificateService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class AsesiCertificateController extends Controller
{
    protected CertificateService $certificateService;

    public function __construct(CertificateService $certificateService)
    {
        $this->certificateService = $certificateService;

        $this->middleware(['auth', 'role:asesi']);
    }

    public function index(): Response
    {
        $certificates = $this->certificateService->getCertificatesForUser(Auth::id(), 15);

        return Inertia::render('CertificateIndex', [
            'certificates' => CertificateResource::collection($certificates),
        ]);
    }

    public function show(int $id): Response
    {
        $certificate = $this->certificateService->getCertificateById($id);
        
        return Inertia::render('CertificateShow', [
            'certificate' => new CertificateResource($certificate->load(['scheme'])),
        ]);
    }

    public function downloadFile(Certificate $certificate)
    {
        /** @var \Illuminate\Filesystem\FilesystemManager $storage */
        $storage = Storage::disk('public');

        if ($certificate->file_path && $storage->exists($certificate->file_path)) {
            return $storage->response($certificate->file_path, basename($certificate->file_path));
        }

        return redirect()->back()->with('error', 'File sertifikat tidak ditemukan.');
    }
}
