<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Planes de Internet') }}
            </h2>
            <a href="{{ route('plans.create') }}" class="w-full sm:w-auto text-center bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold py-2 px-4 rounded shadow transition duration-150">
                + Nuevo Plan
            </a>
        </div>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm text-sm">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <div class="p-4 sm:p-6 text-gray-900">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 sm:px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nombre del Plan</th>
                                    <th class="px-4 sm:px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Velocidad (Bajada/Subida)</th>
                                    <th class="px-4 sm:px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Precio Mensual</th>
                                    <th class="px-4 sm:px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 text-sm">
                                @forelse($plans as $plan)
                                <tr class="hover:bg-gray-50 transition duration-150">
                                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                        {{ $plan->name }}
                                    </td>
                                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-gray-500">
                                        <span class="text-green-600 font-bold">{{ $plan->download_speed }} Mbps</span> ↓ / 
                                        <span class="text-blue-600 font-bold">{{ $plan->upload_speed }} Mbps</span> ↑
                                    </td>
                                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-gray-900 font-bold">
                                        ${{ number_format($plan->price, 2) }}
                                    </td>
                                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-right font-medium">
                                        <a href="{{ route('plans.edit', $plan) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Editar</a>
                                        <form action="{{ route('plans.destroy', $plan) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Estás seguro de eliminar este plan?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-4 sm:px-6 py-4 text-center text-gray-500 text-xs sm:text-sm">
                                        No hay planes registrados. ¡Haz clic en "Nuevo Plan" para comenzar!
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>