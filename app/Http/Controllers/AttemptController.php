<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAttemptRequest;
use App\Http\Resources\SchemeResource;
use App\Services\AttemptService;
use App\Services\SchemeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AttemptController extends Controller
{
    public function __construct(
        protected SchemeService $schemeService,
        protected AttemptService $attemptService
    ) {
        $this->middleware('permission:view pre-assessment')->only(['index', 'show']);
        $this->middleware('permission:create pre-assessment')->only(['create']);
        $this->middleware('permission:submit pre-assessment')->only(['store']);
        $this->middleware('permission:review pre-assessment')->only(['verify']);
    }

    public function index()
    {
        $attempts = $this->attemptService->getAllAttempts();

        return Inertia::render('attempts/Index', [
            'attempts' => $attempts,
            'permissions' => [
                'create' => Auth::user()->can('create pre-assessment'),
                'submit' => Auth::user()->can('submit pre-assessment'),
                'review' => Auth::user()->can('review pre-assessment'),
                'view' => Auth::user()->can('view pre-assessment'),
            ]
        ]);
    }

    public function create(Request $request)
    {
        $schemes = $this->schemeService->getSchemesWithUnitsAndPreAssessments($request->scheme_id);
        
        return Inertia::render('Pendaftaran', [
            'schemes' => SchemeResource::collection($schemes)->resolve(),
            'hasProfile' => $request->user()->profile !== null, // Tambahkan ini
        ]);
    }

    /**
     * Simpan data pendaftaran ke attempt
     */
    public function store(StoreAttemptRequest $request)
    {
        $this->attemptService->createAttempt(
            $request->validated(),
            $request->user()->id
        );

        return redirect()->route('success')->with('success', 'Pendaftaran berhasil');
    }

    /**
     * Tampilkan hasil pendaftaran berdasarkan ID
     */
    public function show(int $id)
    {
        $attempt = $this->attemptService->getAttemptById($id);

        return Inertia::render('attempts/Show', [
            'attempt' => $attempt,
        ]);
    }

    /**
     * Verifikasi pendaftaran: ubah status menjadi 'diterima' atau 'ditolak'
     */
    public function verify(Request $request, int $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        $updated = $this->attemptService->updateStatus($id, $request->input('status'));

        if (!$updated) {
            return redirect()->back()->with('error', 'Pendaftaran tidak ditemukan atau gagal diperbarui');
        }

        return redirect()->back()->with('success', 'Status pendaftaran berhasil diperbarui');
    }
}
