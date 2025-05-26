<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\SchemeRequest;
use App\Models\Scheme;
use App\Models\Unit;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class SchemeController extends Controller
{
    public function index()
    {
        $schemes = Scheme::with('units')->get();

        return inertia('schemes/Index', [
            'schemes' => $schemes,
        ]);
    }

    public function create()
    {
        $units = Unit::all();

        return Inertia::render('schemes/Create', [
            'units' => $units,
        ]);
    }

    public function store(SchemeRequest $request)
    {
        DB::transaction(function () use ($request) {
            $scheme = Scheme::create($request->validated());

            if (!empty($request->unit_ids)) {
                $scheme->units()->attach($request->unit_ids);
            }
        });

        return redirect()->route('schemes.index')
            ->with('success', 'Scheme created successfully');
    }

    public function edit(Scheme $scheme)
    {
        return Inertia::render('schemes/Edit', [
            'scheme' => $scheme->load('units'),
            'units' => Unit::all(),
        ]);
    }

    public function update(SchemeRequest $request, Scheme $scheme)
    {
        DB::transaction(function () use ($request, $scheme) {
            $scheme->update($request->validated());
            $scheme->units()->sync($request->unit_ids);
        });

        return redirect()->route('schemes.index')
            ->with('success', 'Scheme updated successfully');
    }

    public function destroy(Scheme $scheme)
    {
        DB::transaction(function () use ($scheme) {
            $scheme->units()->detach();
            $scheme->delete();
        });

        return redirect()->route('schemes.index')
            ->with('success', 'Scheme deleted successfully');
    }

    public function getAllPreAssessmentsByScheme(SchemeRequest $request)
    {
        $schemes = Scheme::findOrFail($request->id);
        return $schemes->getPreAssessments();
    }
}
