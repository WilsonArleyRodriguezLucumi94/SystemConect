<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Directorio de Clientes') }}
            </h2>
            <a href="{{ route('clients.create') }}" class="w-full sm:w-auto text-center bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold py-2 px-4 rounded shadow transition duration-150">
                + Nuevo Cliente
            </a>
        </div>
    </x-slot>

    <!-- Contenedor con estado Alpine.js para la búsqueda -->
    <div x-data="{ search: '' }" class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4">
            
            <!-- Mostrar alertas de éxito -->
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Campo de Búsqueda -->
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                <div class="relative w-full sm:w-80">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        🔍
                    </div>
                    <input 
                        type="text" 
                        x-model="search" 
                        placeholder="Buscar cliente por nombre o documento..." 
                        class="pl-10 w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >
                </div>
            </div>

            <!-- Tabla de Clientes Responsiva -->
            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <div class="p-4 sm:p-6 text-gray-900">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 sm:px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Documento / Nombre</th>
                                    <th class="px-4 sm:px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Contacto</th>
                                    <th class="px-4 sm:px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Plan / IP</th>
                                    <th class="px-4 sm:px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Estado</th>
                                    <th class="px-4 sm:px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 text-sm">
                                @forelse($clients as $client)
                                <tr 
                                    x-show="!search || '{{ addslashes(mb_strtolower($client->full_name)) }}'.includes(search.toLowerCase()) || '{{ addslashes($client->document_number) }}'.includes(search.toLowerCase())"
                                    class="hover:bg-gray-50 transition duration-150"
                                >
                                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                        <div class="font-bold text-gray-900">{{ $client->full_name }}</div>
                                        <div class="text-xs sm:text-sm text-gray-500">{{ $client->document_number }}</div>
                                    </td>
                                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                        <div class="text-gray-900">{{ $client->phone }}</div>
                                        <div class="text-xs text-gray-500 max-w-xs truncate">{{ $client->address }}</div>
                                    </td>
                                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                        <div class="font-medium text-gray-900">{{ $client->plan->name ?? 'Sin Plan' }}</div>
                                        <div class="text-xs text-gray-500"><code>{{ $client->ipAddress->ip_address ?? 'Sin IP asignada' }}</code></div>
                                    </td>
                                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                        @if($client->status == 'active')
                                            <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Activo</span>
                                        @else
                                            <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Suspendido</span>
                                        @endif
                                    </td>
                                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-right font-medium">
                                        <a href="{{ route('clients.edit', $client) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Editar</a>
                                        <form action="{{ route('clients.destroy', $client) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Seguro que deseas eliminar este cliente?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-4 sm:px-6 py-4 text-center text-gray-500 text-xs sm:text-sm">
                                        No hay clientes registrados.
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