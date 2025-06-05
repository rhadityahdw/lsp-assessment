<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\SchemeRequest;
use App\Models\Scheme;
use App\Models\Unit;
use App\Services\SchemeService;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class SchemeController extends Controller
{
    public function __construct(
        protected SchemeService $schemeService
    ) {
        $this->middleware('permission:view scheme')->only(['index', 'show']);
        $this->middleware('permission:create scheme')->only(['create', 'store']);
        $this->middleware('permission:edit scheme')->only(['edit', 'update']);
        $this->middleware('permission:delete scheme')->only(['destroy']);
    }

    public function index()
    {
        $schemes = $this->schemeService->getAllSchemes();
        return inertia('schemes/Index', [
            'schemes' => $schemes,
            'permissions' => [
                'create' => Auth::user()->can('create scheme'),
                'edit' => Auth::user()->can('edit scheme'),
                'delete' => Auth::user()->can('delete scheme'),
                'view' => Auth::user()->can('view scheme'),
            ]
        ]);
    }

    public function create()
    {
        $units = Unit::all();
        return Inertia::render('schemes/Create', compact('units'));
    }

    public function store(SchemeRequest $request)
    {
        try {
            $this->schemeService->createScheme(
                $request->validated(),
                $request->unit_ids
            );
            
            return redirect()->route('schemes.index')
                ->with('success', 'Scheme created successfully');
                
        } catch (\Exception $e) {
            Log::error('Scheme creation failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to create scheme');
        }
    }

    public function show($id)
    {
        $scheme = $this->schemeService->getSchemeById($id);
        return Inertia::render('schemes/Show', [
            'scheme' => $scheme,
            'units' => $scheme->units,
        ]);
    }

    public function edit(Scheme $scheme)
    {
        $scheme->load('units');
        $units = Unit::all();
        return Inertia::render('schemes/Edit', compact('scheme', 'units'));
    }

    public function update(SchemeRequest $request, Scheme $scheme)
    {
        try {
            $this->schemeService->updateScheme(
                $scheme->id,
                $request->validated(),
                $request->unit_ids
            );
            
            return redirect()->route('schemes.index')
                ->with('success', 'Scheme updated successfully');
                
        } catch (\Exception $e) {
            Log::error('Scheme update failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to update scheme');
        }
    }

    public function destroy(Scheme $scheme)
    {
        try {
            $this->schemeService->deleteScheme($scheme->id);
            return redirect()->route('schemes.index')
                ->with('success', 'Scheme deleted successfully');
                
        } catch (\Exception $e) {
            Log::error('Scheme deletion failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete scheme');
        }
    }
}
