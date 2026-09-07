<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight truncate">
            {{ __('Editar Cliente:') }} <span class="text-indigo-600">{{ $client->full_name }}</span>
        </h2>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4 sm:p-6">

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

                <form action="{{ route('clients.update', $client->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                        <div>
                            <label for="full_name" class="block text-sm font-medium text-gray-700 mb-1">Nombre Completo</label>
                            <input type="text" name="full_name" id="full_name" value="{{ old('full_name', $client->full_name) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" required>
                        </div>

                        <div>
                            <label for="document_number" class="block text-sm font-medium text-gray-700 mb-1">Documento / Dirección MAC</label>
                            <input type="text" name="document_number" id="document_number" value="{{ old('document_number', $client->document_number) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" required>
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone', $client->phone) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        </div>

                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Dirección / Sector</label>
                            <input type="text" name="address" id="address" value="{{ old('address', $client->address) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        </div>

                        <div>
                            <label for="plan_id" class="block text-sm font-medium text-gray-700 mb-1">Plan Asignado</label>
                            <select name="plan_id" id="plan_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" required>
                                @foreach($plans as $plan)
                                    <option value="{{ $plan->id }}" {{ old('plan_id', $client->plan_id) == $plan->id ? 'selected' : '' }}>
                                        {{ $plan->name }} (${{ number_format($plan->price, 0) }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="billing_day" class="block text-sm font-medium text-gray-700 mb-1">Día de Corte (1-31)</label>
                            <input type="number" min="1" max="31" name="billing_day" id="billing_day" value="{{ old('billing_day', $client->billing_day) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" required>
                        </div>

                        <div>
                            <label for="next_due_date" class="block text-sm font-medium text-gray-700 mb-1">Día de Corte / Facturación</label>
                            <input type="date" 
                                name="next_due_date" 
                                id="next_due_date" 
                                value="{{ old('next_due_date', $client->next_due_date ? \Carbon\Carbon::parse($client->next_due_date)->format('Y-m-d') : '') }}" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" 
                                required>
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Estado del Servicio</label>
                            <select name="status" id="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" required>
                                <option value="active" {{ old('status', $client->status) == 'active' ? 'selected' : '' }}>Activo</option>
                                <option value="suspended" {{ old('status', $client->status) == 'suspended' ? 'selected' : '' }}>Suspendido</option>
                                <option value="inactive" {{ old('status', $client->status) == 'inactive' ? 'selected' : '' }}>Inactivo</option>
                            </select>
                        </div>
                    </div>

                    <!-- Botones de Acción -->
                    <div class="flex flex-col-reverse sm:flex-row items-center justify-end gap-3 sm:gap-4 pt-4 sm:pt-6 border-t border-gray-100">
                        <a href="{{ route('clients.index') }}" class="w-full sm:w-auto text-center px-4 py-2 bg-gray-500 text-white text-sm font-semibold rounded-md hover:bg-gray-600 transition duration-150">
                            Cancelar
                        </a>
                        <button type="submit" class="w-full sm:w-auto text-center px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-md hover:bg-indigo-700 transition duration-150">
                            Guardar Cambios
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>