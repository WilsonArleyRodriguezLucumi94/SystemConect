<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editar Cliente: {{ $client->full_name }}
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

                <form action="{{ route('clients.update', $client->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="full_name" class="block text-sm font-medium text-gray-700">Nombre Completo</label>
                            <input type="text" name="full_name" id="full_name" value="{{ old('full_name', $client->full_name) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        </div>

                        <div>
                            <label for="document_number" class="block text-sm font-medium text-gray-700">Documento / Dirección MAC</label>
                            <input type="text" name="document_number" id="document_number" value="{{ old('document_number', $client->document_number) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700">Teléfono</label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone', $client->phone) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700">Dirección / Sector</label>
                            <input type="text" name="address" id="address" value="{{ old('address', $client->address) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label for="plan_id" class="block text-sm font-medium text-gray-700">Plan Asignado</label>
                            <select name="plan_id" id="plan_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                @foreach($plans as $plan)
                                    <option value="{{ $plan->id }}" {{ old('plan_id', $client->plan_id) == $plan->id ? 'selected' : '' }}>
                                        {{ $plan->name }} (${{ number_format($plan->price, 0) }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="billing_day" class="block text-sm font-medium text-gray-700">Día de Corte (1-31)</label>
                            <input type="number" min="1" max="31" name="billing_day" id="billing_day" value="{{ old('billing_day', $client->billing_day) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        </div>

                        <div>
                            <label for="billing_day" class="block text-sm font-medium text-gray-700">Día de Corte / Facturación</label>
                            <input type="date" name="billing_day" id="billing_day" value="{{ old('billing_day', $client->billing_day) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Estado del Servicio</label>
                            <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="active" {{ old('status', $client->status) == 'active' ? 'selected' : '' }}>Activo</option>
                                <option value="suspended" {{ old('status', $client->status) == 'suspended' ? 'selected' : '' }}>Suspendido</option>
                                <option value="inactive" {{ old('status', $client->status) == 'inactive' ? 'selected' : '' }}>Inactivo</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 pt-4">
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white font-semibold rounded-md hover:bg-indigo-700 transition">
                            Guardar Cambios
                        </button>
                        <a href="{{ route('clients.index') }}" class="px-4 py-2 bg-gray-500 text-white font-semibold rounded-md hover:bg-gray-600 transition">
                            Cancelar
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>