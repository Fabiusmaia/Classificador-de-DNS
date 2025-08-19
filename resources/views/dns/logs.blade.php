<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Registros de DNS') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-100 mb-6 text-center">Registros de DNS</h1>

                    <form action="{{ route('dns.logs') }}" method="GET" class="mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                            <div>
                                <label for="domain" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mt-2">Domínio</label>
                                <input type="text" name="domain" id="domain" value="{{ request('domain') }}" placeholder="Ex: google.com" class="mt-3 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:text-white">
                            </div>
                            <div>
                                <label for="ip" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mt-2">IP do Cliente</label>
                                <input type="text" name="ip" id="ip" value="{{ request('ip') }}" placeholder="Ex: 192.168.1.1" class="mt-3 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:text-white">
                            </div>
                            <div>
                                <label for="classification" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mt-2">Classificação</label>
                                <select name="classification" id="classification" class="mt-3 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:text-white">
                                    <option value="">Todas</option>
                                    <option value="seguro" {{ request('classification') == 'seguro' ? 'selected' : '' }}>Seguro</option>
                                    <option value="suspeito" {{ request('classification') == 'suspeito' ? 'selected' : '' }}>Suspeito</option>
                                    <option value="malicioso" {{ request('classification') == 'malicioso' ? 'selected' : '' }}>Malicioso</option>
                                </select>
                            </div>
                            <div>
                                <button type="submit" class="w-full px-4 py-2 bg-indigo-600 text-white font-semibold rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">Filtrar</button>
                            </div>
                        </div>
                    </form>

                    @if($logs->isEmpty())
                    <p class="text-center text-gray-500 dark:text-gray-400 mb-4">Nenhum log de DNS encontrado.</p>
                    @else
                    <div class="overflow-x-auto rounded-lg shadow">
                        <table class="min-w-full w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class=" bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Domínio</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">IP do Cliente</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Hora da Consulta</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Classificação</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($logs as $log)
                                @php
                                $classificationStyle = match(strtolower($log->classification)) {
                                'seguro' => 'background-color: #10b981; color: white;',
                                'suspeito' => 'background-color: #facc15; color: black;',
                                'malicioso' => 'background-color: #ef4444; color: white;',
                                default => 'background-color: #9ca3af; color: white;',
                                };
                                @endphp
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ $log->domain }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $log->client_ip }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $log->queried_at }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" style="{{ $classificationStyle }}">
                                            {{ $log->classification }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>