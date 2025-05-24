<?php

namespace App\Http\Controllers;

use App\Models\Attempt;
use App\Http\Controllers\Controller;
use Inertia\Inertia;

class AttemptController extends Controller
{
    public function show($id)
    {
        $attempt = Attempt::with([
            'user.profile',
            'scheme.units.preAssessments',
            'preAssessmentAnswers'
        ])->findOrFail($id);

        return Inertia::render('attempts/Show', [
            'attempt' => $attempt,
            'meta' => [
                'title' => 'Detail Attempt #' . $id
            ]
        ]);
    }
}
