<?php

namespace App\Jobs;

use App\Models\DnsLog;
use App\Services\DnsLogService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessDnsDomain implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 5;
    public $backoff = [10, 30, 60, 120];

    protected string $domain;
    protected string $timestamp;
    protected string $clientIp;
    protected int $userId;

    public function __construct(string $domain, string $timestamp, string $clientIp, int $userId)
    {
        $this->domain = $domain;
        $this->timestamp = $timestamp;
        $this->clientIp = $clientIp;
        $this->userId = $userId;
    }

    public function handle(DnsLogService $dnsLogService)
    {
        try {
            usleep(500_000); 

            $classification = $dnsLogService->callAiApi($this->domain);

            DnsLog::create([
                'user_id' => $this->userId,
                'domain' => $this->domain,
                'client_ip' => $this->clientIp,
                'queried_at' => $this->timestamp,
                'classification' => $classification
            ]);
        } catch (\Exception $e) {
            Log::error("Erro ao processar domínio '{$this->domain}': " . $e->getMessage());
            throw $e; 
        }
    }
}
