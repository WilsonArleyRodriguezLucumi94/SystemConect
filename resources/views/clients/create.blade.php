<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Registrar Nuevo Cliente') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                
                <form action="{{ route('clients.store') }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Documento -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">N° de Documento</label>
                            <input type="text" name="document_number" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <!-- Nombre Completo -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nombre Completo</label>
                            <input type="text" name="full_name" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <!-- Teléfono -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Teléfono / WhatsApp</label>
                            <input type="text" name="phone" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <!-- Dirección -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Dirección de Instalación</label>
                            <input type="text" name="address" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <!-- Selección de IP Disponible con Buscador -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Dirección IP (Zona / VLAN)</label>
                            
                            <select id="ip-select" name="ip_address_id" placeholder="Escribe para buscar IP, Zona o VLAN..." autocomplete="off" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Sin IP asignada</option>
                                @foreach($ips as $ip)
                                    <option value="{{ $ip->id }}">
                                        {{ $ip->ip_address }} ({{ $ip->zone }} - VLAN: {{ $ip->vlan ?? 'N/A' }})
                                    </option>
                                @endforeach
                            </select>
                            
                            <span class="text-xs text-gray-500">Puedes buscar por número de IP, nombre de la red o VLAN.</span>
                        </div>

                        <!-- Selección de Plan -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Plan Contratado</label>
                            <select name="plan_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Seleccione un plan...</option>
                                @foreach($plans as $plan)
                                    <option value="{{ $plan->id }}">{{ $plan->name }} - ${{ number_format($plan->price, 2) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Día de Facturación -->
                        <div class="col-span-2 md:col-span-1">
                            <label class="block text-sm font-medium text-gray-700">Fecha del primer cobro</label>
                            <input type="date" name="billing_day" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <span class="text-xs text-gray-500">A partir de este día se generarán las facturas mensuales.</span>
                        </div>
                        <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="router_id">
                                    Router / Nodo Asignado
                                </label>
                                <select name="router_id" id="router_id" required class="w-full border rounded px-3 py-2 text-gray-700 focus:outline-none focus:border-blue-500">
                                    <option value="">-- Seleccione un Router --</option>
                                    @foreach($routers as $router)
                                        <option value="{{ $router->id }}" {{ old('router_id') == $router->id ? 'selected' : '' }}>
                                            {{ $router->name }} ({{ $router->ip_address }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <a href="{{ route('clients.index') }}" class="mr-4 text-gray-600 hover:text-gray-900 px-4 py-2">Cancelar</a>
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-md shadow">
                            Registrar Cliente
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>



    <!-- Agregamos los estilos y scripts de Tom Select -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            new TomSelect('#ip-select', {
                create: false, // No permite crear IPs que no existan en la lista
                sortField: {
                    field: "text",
                    direction: "asc" // Ordena alfabéticamente/numéricamente
                }
            });
        });
    </script>
</x-app-layout>