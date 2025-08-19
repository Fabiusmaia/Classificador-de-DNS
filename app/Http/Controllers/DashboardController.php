<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DashboardService;

class DashboardController extends Controller
{
    protected $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    /**
     * Exibe o dashboard com métricas de logs DNS.
     */
    public function index(Request $request)
    {
        $data = $this->dashboardService->getDashboardMetrics();

        return view('dashboard', $data);
    }
}
