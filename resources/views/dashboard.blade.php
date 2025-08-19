<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Mensagem de boas-vindas -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("Bem-vindo ao painel de controle!") }}
                </div>
            </div>

            <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 text-center mb-2">
                    <h4 class="text-gray-700 dark:text-gray-300 font-semibold">Total de Logs</h4>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $totalLogs }}</p>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 mb-2">
                    <h4 class="text-gray-700 dark:text-gray-300 font-semibold mb-2">Percentual por Categoria</h4>
                    <ul>
                        <li class="text-white dark:text-black">Seguro: {{ $percentSafe }}%</li>
                        <li class="text-white dark:text-black">Suspeito: {{ $percentSuspicious }}%</li>
                        <li class="text-white dark:text-black">Malicioso: {{ $percentMalicious }}%</li>
                    </ul>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h4 class="text-gray-700 dark:text-gray-300 font-semibold mb-2">Últimos 10 Domínios Maliciosos</h4>
                    <ol class="list-decimal list-inside">
                        @foreach($lastMaliciousDomains as $log)
                        <li class="text-white dark:text-black">{{ $log->domain }} ({{ $log->queried_at->format('Y-m-d H:i') }})</li>
                        @endforeach
                    </ol>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>