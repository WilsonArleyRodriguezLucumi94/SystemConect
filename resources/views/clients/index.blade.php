<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Directorio de Clientes') }}
            </h2>
            <a href="{{ route('clients.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold py-2 px-4 rounded shadow">
                + Nuevo Cliente
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Mostrar alertas de éxito -->
            @if(session('success'))
                <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Documento / Nombre</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Contacto</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Plan / IP</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($clients as $client)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-900">{{ $client->full_name }}</div>
                                <div class="text-sm text-gray-500">{{ $client->document_number }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ $client->phone }}</div>
                                <div class="text-xs text-gray-500">{{ $client->address }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 font-medium">{{ $client->plan->name }}</div>
                                {{-- Asumiendo que estás dentro de un foreach ($clients as $client) --}}
                                {{ $client->ipAddress->ip_address ?? 'Sin IP asignada' }}
                            </td>
                            <td class="px-6 py-4">
                                @if($client->status == 'active')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Activo</span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Suspendido</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm font-medium">
                                <a href="{{ route('clients.edit', $client) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Editar</a>
                                <!-- Para eliminar requerimos un mini formulario -->
                                <form action="{{ route('clients.destroy', $client) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Seguro que deseas eliminar este cliente?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>