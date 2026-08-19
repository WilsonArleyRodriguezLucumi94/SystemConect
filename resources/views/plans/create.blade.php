<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Crear Nuevo Plan de Internet') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                
                <form action="{{ route('plans.store') }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nombre del Plan -->
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Nombre del Plan</label>
                            <input type="text" name="name" placeholder="Ej. Básico 50 Megas" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <!-- Velocidad de Bajada -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Velocidad de Bajada (Mbps)</label>
                            <input type="number" name="download_speed" placeholder="50" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <!-- Velocidad de Subida -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Velocidad de Subida (Mbps)</label>
                            <input type="number" name="upload_speed" placeholder="25" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <!-- Precio -->
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Precio Mensual ($)</label>
                            <input type="number" step="0.01" name="price" placeholder="60000" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <a href="{{ route('plans.index') }}" class="mr-4 text-gray-600 hover:text-gray-900 px-4 py-2">Cancelar</a>
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-md shadow">
                            Guardar Plan
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>