<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight">
            {{ __('Registrar Nuevo Cliente') }}
        </h2>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-4 sm:p-6">
                
                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded-r shadow-sm text-sm">
                        <p class="font-bold mb-1">Por favor corrige los siguientes errores:</p>
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('clients.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                        <!-- Documento -->
                        <div>
                            <label for="document_number" class="block text-sm font-medium text-gray-700 mb-1">N° de Documento</label>
                            <input type="text" 
                                name="document_number" 
                                id="document_number" 
                                value="{{ old('document_number') }}" 
                                required
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm @error('document_number') border-red-500 @enderror">
                            @error('document_number')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nombre Completo -->
                        <div>
                            <label for="full_name" class="block text-sm font-medium text-gray-700 mb-1">Nombre Completo</label>
                            <input type="text" 
                                name="full_name" 
                                id="full_name" 
                                value="{{ old('full_name') }}" 
                                required
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        </div>

                        <!-- Teléfono -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Teléfono / WhatsApp</label>
                            <input type="text" 
                                name="phone" 
                                id="phone" 
                                value="{{ old('phone') }}" 
                                required
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        </div>

                        <!-- Dirección -->
                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Dirección de Instalación</label>
                            <input type="text" 
                                name="address" 
                                id="address" 
                                value="{{ old('address') }}" 
                                required
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        </div>

                        <!-- Selección de IP Disponible con Buscador -->
                        <div>
                            <label for="ip-select" class="block text-sm font-medium text-gray-700 mb-1">Dirección IP (Zona / VLAN)</label>
                            <select id="ip-select" name="ip_address_id" placeholder="Escribe para buscar IP, Zona o VLAN..." autocomplete="off" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                <option value="">Sin IP asignada</option>
                                @foreach($ips as $ip)
                                    <option value="{{ $ip->id }}" {{ old('ip_address_id') == $ip->id ? 'selected' : '' }}>
                                        {{ $ip->ip_address }} ({{ $ip->zone }} - VLAN: {{ $ip->vlan ?? 'N/A' }})
                                    </option>
                                @endforeach
                            </select>
                            <span class="mt-1 block text-xs text-gray-500">Puedes buscar por número de IP, nombre de la red o VLAN.</span>
                        </div>

                        <!-- Selección de Plan -->
                        <div>
                            <label for="plan_id" class="block text-sm font-medium text-gray-700 mb-1">Plan Contratado</label>
                            <select name="plan_id" id="plan_id" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                <option value="">Seleccione un plan...</option>
                                @foreach($plans as $plan)
                                    <option value="{{ $plan->id }}" {{ old('plan_id') == $plan->id ? 'selected' : '' }}>
                                        {{ $plan->name }} - ${{ number_format($plan->price, 2) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Día de Facturación -->
                        <div>
                            <label for="billing_day" class="block text-sm font-medium text-gray-700 mb-1">Fecha del primer cobro</label>
                            <input type="date" 
                                name="billing_day" 
                                id="billing_day" 
                                value="{{ old('billing_day') }}" 
                                required
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            <span class="mt-1 block text-xs text-gray-500">A partir de este día se generarán las facturas mensuales.</span>
                        </div>

                        <!-- Router Asignado -->
                        <div>
                            <label for="router_id" class="block text-sm font-medium text-gray-700 mb-1">Router / Nodo Asignado</label>
                            <select name="router_id" id="router_id" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                <option value="">-- Seleccione un Router --</option>
                                @foreach($routers as $router)
                                    <option value="{{ $router->id }}" {{ old('router_id') == $router->id ? 'selected' : '' }}>
                                        {{ $router->name }} ({{ $router->ip_address }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Botones de Acción -->
                    <div class="flex flex-col-reverse sm:flex-row items-center justify-end gap-3 sm:gap-4 pt-4 sm:pt-6 border-t border-gray-100">
                        <a href="{{ route('clients.index') }}" class="w-full sm:w-auto text-center px-4 py-2 bg-gray-500 text-white text-sm font-semibold rounded-md hover:bg-gray-600 transition duration-150">
                            Cancelar
                        </a>
                        <button type="submit" class="w-full sm:w-auto text-center px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-md hover:bg-indigo-700 transition duration-150">
                            Registrar Cliente
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- Estilos y Scripts de Tom Select -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            new TomSelect('#ip-select', {
                create: false,
                sortField: {
                    field: "text",
                    direction: "asc"
                }
            });
        });
    </script>
</x-app-layout>