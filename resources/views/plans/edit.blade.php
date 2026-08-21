<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editar Plan: {{ $plan->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('plans.update', $plan->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Nombre del Plan</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $plan->name) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    </div>

                    <div>
                        <label for="download_speed" class="block text-sm font-medium text-gray-700">Velocidad de Bajada (Mbps)</label>
                        <input type="number" name="download_speed" id="download_speed" value="{{ old('download_speed', $plan->download_speed) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    </div>

                    <div>
                        <label for="upload_speed" class="block text-sm font-medium text-gray-700">Velocidad de Subida (Mbps)</label>
                        <input type="number" name="upload_speed" id="upload_speed" value="{{ old('upload_speed', $plan->upload_speed) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    </div>

                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700">Precio ($)</label>
                        <input type="number" step="0.01" name="price" id="price" value="{{ old('price', $plan->price) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    </div>

                    <div class="flex items-center gap-4">
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white font-semibold rounded-md hover:bg-indigo-700 transition">
                            Guardar Cambios
                        </button>
                        <a href="{{ route('plans.index') }}" class="px-4 py-2 bg-gray-500 text-white font-semibold rounded-md hover:bg-gray-600 transition">
                            Cancelar
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>