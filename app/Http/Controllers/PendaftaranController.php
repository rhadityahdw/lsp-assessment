<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\SchemeService;
use Illuminate\Http\Request;

class PendaftaranController extends Controller
{
    protected $schemeService;

    public function __construct(SchemeService $schemeService)
    {
        $this->schemeService = $schemeService;
    }

    public function getAllSchemes()
    {
        $schemes = $this->schemeService->getAllSchemes();

        return $schemes;
    }
}
