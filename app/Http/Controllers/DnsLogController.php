<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DnsLogService;
use Illuminate\Support\Facades\Auth;

class DnsLogController extends Controller
{
    protected $dnsLogService;

    public function __construct(DnsLogService $dnsLogService)
    {
        $this->dnsLogService = $dnsLogService;
    }

    public function showUploadForm()
    {
        return view('csv.upload'); 
    }

    public function index(Request $request)
    {
        $userId = Auth::id();

        $filters = $request->only(['domain', 'ip', 'classification']);

        $logs = $this->dnsLogService->getFilteredLogs($userId, $filters);

        return view('dns.logs', ['logs' => $logs]);
    }

    public function uploadCsv(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt',
        ]);

        $file = $request->file('csv_file');
        $userId = Auth::id();

        $results = $this->dnsLogService->processCsv($file, $userId);

        return response()->json([
            'message' => 'Arquivo processado com sucesso!',
            'processed' => count($results),
            'results' => $results
        ]);
    }
}
