<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Registrar Nuevo Gasto') }}
        </h2>
    </x-slot>

    <div class="py-8 max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded text-sm">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('expenses.store') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Descripción del Gasto *</label>
                    <input type="text" name="description" id="description" value="{{ old('description') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Ejemplo: Compra de Bobina de Fibra Óptica 1KM" required>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="amount" class="block text-sm font-medium text-gray-700">Monto ($) *</label>
                        <input type="number" step="0.01" min="0.01" name="amount" id="amount" value="{{ old('amount') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="0.00" required>
                    </div>

                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700">Categoría</label>
                        <select name="category" id="category" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="Equipos y Redes" {{ old('category') == 'Equipos y Redes' ? 'selected' : '' }}>Equipos y Redes</option>
                            <option value="Mantenimiento" {{ old('category') == 'Mantenimiento' ? 'selected' : '' }}>Mantenimiento</option>
                            <option value="Servicios Públicos" {{ old('category') == 'Servicios Públicos' ? 'selected' : '' }}>Servicios Públicos</option>
                            <option value="Nómina / Pagos" {{ old('category') == 'Nómina / Pagos' ? 'selected' : '' }}>Nómina / Pagos</option>
                            <option value="Combustible / Transporte" {{ old('category') == 'Combustible / Transporte' ? 'selected' : '' }}>Combustible / Transporte</option>
                            <option value="Otros" {{ old('category') == 'Otros' ? 'selected' : '' }}>Otros</option>
                        </select>
                    </div>

                    <div>
                        <label for="expense_date" class="block text-sm font-medium text-gray-700">Fecha del Gasto *</label>
                        <input type="date" name="expense_date" id="expense_date" value="{{ old('expense_date', date('Y-m-d')) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    </div>
                </div>

                <div class="flex items-center gap-4 pt-4 border-t border-gray-100">
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white font-semibold rounded-md hover:bg-indigo-700 transition">
                        Guardar Gasto
                    </button>
                    <a href="{{ route('expenses.index') }}" class="px-4 py-2 bg-gray-500 text-white font-semibold rounded-md hover:bg-gray-600 transition">
                        Cancelar
                    </a>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>