<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("You're logged in!") }}
                </div>
            </div>

            <div class="mt-4 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Ferramentas</h3>
                    <div class="flex flex-col space-y-2">
                        <a href="{{ route('csv.upload.form') }}" class="inline-block px-4 py-2 text-center text-white bg-blue-600 rounded-md hover:bg-blue-700 transition duration-150 ease-in-out">
                            Fazer Upload de CSV
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>