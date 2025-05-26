<?php

namespace App\Http\Controllers;

use App\Models\Attempt;
use App\Http\Controllers\Controller;
use App\Models\PreAssessmentAnswer;
use App\Services\SchemeService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AttemptController extends Controller
{
    public function __construct(
        protected SchemeService $schemeService
    ) {}

    public function create(Request $request)
    {
        $schemes = $this->schemeService->getAllSchemes($request->scheme_id);
        
        return Inertia::render('Pendaftaran', [
            'schemes' => $schemes,
        ]);
    }

    /**
     * Simpan data pendaftaran ke attempt
     */
    public function store(Request $request)
    {
        $request->validate([
            'scheme_id' => 'required|exists:schemes,id',
            'ktp' => 'required|file',
            'ijazah' => 'required|file',
            'pas_foto' => 'required|file',
            'bukti_kerja' => 'nullable|file',
            'portofolio' => 'nullable|file',
            'answers' => 'required|array',
        ]);

        $attempt = Attempt::create([
            'user_id' => auth()->user->id,
            'scheme_id' => $request->scheme_id,
            'ktp' => $request->file('ktp')->store('documents'),
            'ijazah' => $request->file('ijazah')->store('documents'),
            'pas_foto' => $request->file('pas_foto')->store('documents'),
            'bukti_kerja' => $request->file('bukti_kerja')->store('documents'),
            'portofolio' => $request->file('portofolio')->store('documents'),
            'status' => 'submitted',
        ]);

        foreach ($request->pre_assessments as $answer) {
            PreAssessmentAnswer::create([
                'attempt_id' => $attempt->id,
                'pre_assessment_id' => $answer['id'],
                'answer' => $answer['answer'],
            ]);
        }

        return redirect()->back()->with('success', 'Pendaftaran berhasil');
    }
}
