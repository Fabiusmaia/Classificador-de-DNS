<?php

namespace App\Services;

use App\Models\DnsLog;

class DashboardService
{
    /**
     * Retorna os dados do dashboard.
     */
    public function getDashboardMetrics(): array
    {
        $totalLogs = DnsLog::count();

        $totalSafe = DnsLog::where('classification', 'Seguro')->count();
        $totalSuspicious = DnsLog::where('classification', 'Suspeito')->count();
        $totalMalicious = DnsLog::where('classification', 'Malicioso')->count();

        $percentSafe = $totalLogs ? round(($totalSafe / $totalLogs) * 100, 2) : 0;
        $percentSuspicious = $totalLogs ? round(($totalSuspicious / $totalLogs) * 100, 2) : 0;
        $percentMalicious = $totalLogs ? round(($totalMalicious / $totalLogs) * 100, 2) : 0;

        $lastMaliciousDomains = DnsLog::where('classification', 'Malicioso')
            ->orderBy('queried_at', 'desc')
            ->take(10)
            ->get();

        return [
            'totalLogs' => $totalLogs,
            'percentSafe' => $percentSafe,
            'percentSuspicious' => $percentSuspicious,
            'percentMalicious' => $percentMalicious,
            'lastMaliciousDomains' => $lastMaliciousDomains,
        ];
    }
}
