<?php

namespace App\Services;

use App\Jobs\ProcessDnsDomain;
use App\Models\DnsLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DnsLogService
{
    /**
     * Processa um CSV e envia cada domínio para a API.
     */
    public function processCsv($file, $userId)
    {
        $handle = fopen($file->getRealPath(), 'r');

        if ($handle === false) {
            return [];
        }

        $header = fgetcsv($handle, 0, ';');
        $results = [];

        while (($row = fgetcsv($handle, 0, ';')) !== false) {
            if (is_array($row) && count($row) >= 3) {
                [$rawTimestamp, $domain, $clientIp] = $row;
                $timestamp = Carbon::createFromFormat('d/m/Y H:i', $rawTimestamp)
                    ->format('Y-m-d H:i:s');
                ProcessDnsDomain::dispatch($domain, $timestamp, $clientIp, $userId)->delay(now()->addMilliseconds(200));;

                $results[] = ['domain' => $domain, 'status' => 'Dispatched'];
            }
        }

        fclose($handle);

        return $results;
    }

    /**
     * Busca os logs de DNS de um usuário específico.
     *
     * @param int $userId O ID do usuário.
     * @param array $filters Array associativo contendo filtros
     * @return \Illuminate\Database\Eloquent\Collection Uma coleção de DnsLog modelos.
     */
    public function getFilteredLogs($userId, $filters)
    {
        $query = DnsLog::where('user_id', $userId);

        if (!empty($filters['domain'])) {
            $query->where('domain', 'like', '%' . $filters['domain'] . '%');
        }

        if (!empty($filters['ip'])) {
            $query->where('client_ip', $filters['ip']);
        }

        if (!empty($filters['classification'])) {
            $classification = ucfirst(strtolower($filters['classification']));
            $query->where('classification', $classification);
        }

        return $query->orderBy('queried_at', 'desc')->get();
    }
    /**
     * Chama a API de IA para classificar o domínio.
     * @param string $domain O domínio a ser classificado.
     * @return string A classificação do domínio ('Seguro', 'Suspeito' ou 'Malicioso').
     * @throws \Exception Se a chave da API estiver ausente ou a chamada à API falhar.
     */
    public function callAiApi(string $domain): string
    {
        $apiKey = env('GEMINI_API_KEY');

        if (empty($apiKey)) {
            throw new \Exception("A chave da API do Gemini não foi encontrada.");
        }

        $ip = 'N/A';
        $mxRecords = 'N/A';
        $nsRecords = 'N/A';

        try {
            $aRecords = @dns_get_record($domain, DNS_A);
            if ($aRecords && !empty($aRecords[0]['ip'])) {
                $ip = $aRecords[0]['ip'];
            }

            $mx = @dns_get_record($domain, DNS_MX);
            if ($mx) {
                $mxRecords = implode(', ', array_column($mx, 'target'));
            }

            $ns = @dns_get_record($domain, DNS_NS);
            if ($ns) {
                $nsRecords = implode(', ', array_column($ns, 'target'));
            }
        } catch (\ErrorException $e) {
        }

        $prompt = "Classifique o domínio '$domain' como 'Seguro', 'Suspeito' ou 'Malicioso'. Responda apenas com a palavra de classificação. Use o nome do domínio, seu TLD, o IP associado, registros MX e NS.

        Classifique o seguinte domínio:
        - Domínio: $domain
        - IP: $ip
        - Registros MX: $mxRecords
        - Registros NS: $nsRecords
        - Classificação:";

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'X-goog-api-key' => $apiKey,
        ])->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent', [
            'contents' => [
                [
                    'parts' => [
                        [
                            'text' => $prompt
                        ]
                    ]
                ]
            ],
            'generationConfig' => [
                'temperature' => 0.2,
            ],
        ]);

        if ($response->status() === 429) {
            throw new \Exception("429 Too Many Requests - limite atingido");
        }

        if (!$response->successful()) {
            throw new \Exception("A chamada à API do Gemini falhou com o status: " . $response->status());
        }

        $candidates = $response->json('candidates');
        if (empty($candidates) || !isset($candidates[0]['content']['parts'][0]['text'])) {
            throw new \Exception("Resposta da API do Gemini inválida ou sem conteúdo.");
        }

        $text = $candidates[0]['content']['parts'][0]['text'];
        $text = ucfirst(strtolower(trim($text)));

        if (!in_array($text, ['Seguro', 'Suspeito', 'Malicioso'])) {
            return 'Suspeito';
        }
        return $text;
    }
}
