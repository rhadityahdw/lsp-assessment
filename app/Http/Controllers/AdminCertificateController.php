<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCertificateRequest;
use App\Http\Resources\CertificateResource;
use App\Http\Resources\SchemeResource;
use App\Http\Resources\UserResource;
use App\Models\Certificate;
use App\Models\Scheme;
use App\Models\User;
use App\Services\CertificateService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class AdminCertificateController extends Controller
{
    protected CertificateService $certificateService;

    public function __construct(CertificateService $certificateService)
    {
        $this->certificateService = $certificateService;

        $this->middleware(['auth', 'role:admin']);
    }

    public function index(): Response
    {
        $certificates = $this->certificateService->getAllCertificates(10);

        return Inertia::render('Certificates/Index', [
            'certificates' => CertificateResource::collection($certificates),
        ]);
    }

    public function create()
    {
        $users = UserResource::collection(User::whereHas('roles', function ($query) {
            $query->where('name', 'asesi');
        })->orderBy('name')->get());
        $schemes = SchemeResource::collection(Scheme::orderBy('name')->get());

        return Inertia::render('Certificates/Create', [
            'users' => $users,
            'schemes' => $schemes,
        ]);
    }

    public function store(StoreCertificateRequest $request): RedirectResponse
    {
        try {
            $data = $request->validated();
            $data['file_path'] = $request->file('file_path');
            
            $this->certificateService->createCertificate($data);
    
            return redirect()->route('admin.certificates.index')->with('success', 'Sertifikat berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()
                             ->with('error', 'Gagal menambahkan sertifikat: '. $e->getMessage())
                             ->withInput();
        }
    }

    public function show(int $id): Response
    {
        $certificate = $this->certificateService->getCertificateById($id);
        
        return Inertia::render('Certificates/Show', [
            'certificate' => new CertificateResource($certificate)
        ]);
    }

    public function edit(int $id): Response
    {
        $users = UserResource::collection(User::orderBy('name')->get());
        $schemes = SchemeResource::collection(Scheme::orderBy('name')->get());

        $certificate = $this->certificateService->getCertificateById($id);

        return Inertia::render('Certificates/Edit', [
            'certificate' => new CertificateResource($certificate),
            'users' => $users,
            'schemes' => $schemes,
        ]);
    }

    public function update(StoreCertificateRequest $request, Certificate $certificate): RedirectResponse
    {
        try {
            $data = $request->validated();
            $data['file_path'] = $request->file('file_path');

            $this->certificateService->updateCertificate($certificate, $data);

            return redirect()->route('admin.certificates.index')->with('success', 'Sertifikat berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()
                             ->with('error', 'Gagal memperbarui sertifikat: '. $e->getMessage())
                             ->withInput();
        }
    }

    public function destroy(Certificate $certificate): RedirectResponse
    {
        try {
            $this->certificateService->deleteCertificate($certificate);

            return redirect()->route('admin.certificates.index')->with('success', 'Sertifikat berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()
                             ->with('error', 'Gagal menghapus sertifikat: '. $e->getMessage());
        }
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
