<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestión de Routers MikroTik') }}
        </h2>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            <!-- Encabezado y Acción -->
            <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4">
                <h1 class="text-2xl font-bold text-gray-800">Routers Configurados</h1>
                <button onclick="document.getElementById('modal-create').classList.remove('hidden')" 
                        class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow transition duration-150 text-center">
                    + Nuevo Router
                </button>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Tabla de Routers Responsiva -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 sm:p-6 text-gray-900">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                    <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP / Host (VPS Port)</th>
                                    <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuario API</th>
                                    <th class="px-4 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 text-sm">
                                @forelse($routers as $router)
                                <tr class="hover:bg-gray-50 transition duration-150">
                                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ $router->name }}</td>
                                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-gray-600"><code>{{ $router->ip_address }}</code></td>
                                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-gray-600">{{ $router->username }}</td>
                                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-right font-medium">
                                        <form action="{{ route('routers.destroy', $router) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar este router?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 font-semibold">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-4 sm:px-6 py-4 text-center text-gray-500 text-xs sm:text-sm">No hay routers registrados.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $routers->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Crear Router Responsivo -->
    <div id="modal-create" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex items-center justify-center p-4 z-50 overflow-y-auto">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6 my-8 max-h-[90vh] overflow-y-auto">
            <h2 class="text-xl font-bold mb-4 text-gray-800">Agregar Nuevo Router</h2>
            
            <form action="{{ route('routers.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Nombre Identificador</label>
                    <input type="text" name="name" required placeholder="Ej: RB Nodo Norte" class="w-full border border-gray-300 rounded px-3 py-2 text-gray-700 focus:outline-none focus:border-blue-500">
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">IP / Host y Puerto AWS</label>
                    <input type="text" name="ip_address" required placeholder="Ej: 54.210.12.3:8001" class="w-full border border-gray-300 rounded px-3 py-2 text-gray-700 focus:outline-none focus:border-blue-500">
                    <span class="text-xs text-gray-500 mt-1 block">Usa la IP de AWS con el puerto mapeado (ej: IP_AWS:8001).</span>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Usuario REST API</label>
                    <input type="text" name="username" required placeholder="admin" class="w-full border border-gray-300 rounded px-3 py-2 text-gray-700 focus:outline-none focus:border-blue-500">
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Contraseña REST API</label>
                    <input type="password" name="password" required class="w-full border border-gray-300 rounded px-3 py-2 text-gray-700 focus:outline-none focus:border-blue-500">
                </div>

                <div class="flex flex-col-reverse sm:flex-row justify-end sm:space-x-2 gap-2 sm:gap-0">
                    <button type="button" onclick="document.getElementById('modal-create').classList.add('hidden')" class="w-full sm:w-auto px-4 py-2 text-gray-600 hover:text-gray-800 text-center">Cancelar</button>
                    <button type="submit" class="w-full sm:w-auto px-4 py-2 bg-blue-600 text-white rounded font-bold hover:bg-blue-700">Guardar Router</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>