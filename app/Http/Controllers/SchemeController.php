<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Scheme;
use App\Models\Unit;
use App\Services\SchemeService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SchemeController extends Controller
{
    protected $schemeService;

    public function __construct(SchemeService $schemeService)
    {
        $this->schemeService = $schemeService;
    }
    
    /**
     * Get all schemes
     * 
     * @return \Inertia\Response
     */
    public function index()
    {
        $schemes = $this->schemeService->getAllSchemes();

        return inertia('schemes/Index', [
            'schemes' => $schemes,
        ]);
    }

    /**
     * Show the form for creating a new scheme
     *
     * @return \Inertia\Response
     */
    public function create()
    {
        $units = Unit::all();

        return Inertia::render('schemes/Create', [
            'units' => $units,
        ]);
    }

    /**
     * Store a newly created scheme in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'unit_ids' => 'required|array',
            'unit_ids.*' => 'exists:units,id',
            'document_path' => 'nullable|string|max:255',
            'summary' => 'string|max:255',
        ]);

        $schemeData = [
            'code' => $validated['code'],
            'name' => $validated['name'],
            'type' => $validated['type'],
            'document_path' => $validated['document_path'] ?? null,
            'summary' => $validated['summary'],
        ];

        $unitIds = $validated['unit_ids'];

        $this->schemeService->createScheme($schemeData, $unitIds);

        return redirect()->route('schemes.index')
            ->with('message', 'Skema berhasil dibuat');
    }

    /**
     * Display the specified scheme.
     *
     * @param  int  $id
     * @return \Inertia\Response
     */
    public function show($id)
    {
        $scheme = $this->schemeService->getSchemeById($id);

        return Inertia::render('schemes/Show', [
            'scheme' => $scheme,
        ]);
    }

    /**
     * Show the form for editing the specified scheme.
     *
     * @param  int  $id
     * @return \Inertia\Response
     */
    public function edit($id)
    {
        $scheme = $this->schemeService->getSchemeById($id);
        $units = Unit::all();

        return Inertia::render('schemes/Edit', [
            'scheme' => $scheme,
            'units' => $units,
        ]);
    }

    /**
     * Update the specified scheme in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'unit_ids' => 'required|array',
            'unit_ids.*' => 'exists:units,id',
            'document_path' => 'required|string|max:255',
            'summary' => 'required|string|max:255',
        ]);

        $schemeData = [
            'code' => $validated['code'],
            'name' => $validated['name'],
            'type' => $validated['type'],
            'document_path' => $validated['document_path'],
            'summary' => $validated['summary'],
        ];

        $unitIds = $validated['unit_ids'];

        $this->schemeService->updateScheme($id, $schemeData, $unitIds);

        return redirect()->route('schemes.index')
            ->with('message', 'Skema berhasil diperbarui');
    }

    /**
     * Remove the specified scheme from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $this->schemeService->deleteScheme($id);

        return redirect()->route('schemes.index')
            ->with('message', 'Skema berhasil dihapus');
    }
}
