<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Panel de Control ISP') }}
            </h2>

            <!-- Botón para ejecutar la acción -->
            <form action="{{ route('invoices.generate-daily') }}" method="POST" onsubmit="return confirm('¿Deseas procesar los cobros pendientes del día de hoy?');">
                @csrf
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md transition duration-200 flex items-center gap-2 text-sm">
                    ⚡ Generar Cobros del Día
                </button>
            </form>
        </div>

        <!-- Mensajes de confirmación -->
        @if (session('success'))
            <div class="mt-3 p-3 bg-green-100 border border-green-400 text-green-700 rounded text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mt-3 p-3 bg-red-100 border border-red-400 text-red-700 rounded text-sm">
                {{ session('error') }}
            </div>
        @endif
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
        
        <!-- Tarjetas de Resumen Financiero -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Ingresos Totales / Mes -->
            <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-green-500">
                <h3 class="text-gray-500 text-xs font-bold uppercase">Ingresos Totales</h3>
                <p class="text-2xl font-black text-green-600 mt-1">${{ number_format($totalIncome ?? $monthlyIncome, 2) }}</p>
                <p class="text-xs text-gray-400 mt-1">Este mes: ${{ number_format($monthlyIncome, 2) }}</p>
            </div>

            <!-- Gastos Totales / Mes -->
            <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-red-500">
                <h3 class="text-gray-500 text-xs font-bold uppercase">Gastos Totales</h3>
                <p class="text-2xl font-black text-red-600 mt-1">${{ number_format($totalExpenses ?? $monthlyExpenses, 2) }}</p>
                <p class="text-xs text-gray-400 mt-1">Este mes: ${{ number_format($monthlyExpenses ?? 0, 2) }}</p>
            </div>

            <!-- Balance Neto -->
            <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 {{ ($totalNetBalance ?? $monthlyNetBalance ?? 0) >= 0 ? 'border-blue-500' : 'border-yellow-500' }}">
                <h3 class="text-gray-500 text-xs font-bold uppercase">Ganancia Neta</h3>
                <p class="text-2xl font-black {{ ($totalNetBalance ?? $monthlyNetBalance ?? 0) >= 0 ? 'text-blue-600' : 'text-yellow-600' }} mt-1">
                    ${{ number_format($totalNetBalance ?? $monthlyNetBalance ?? 0, 2) }}
                </p>
                <p class="text-xs text-gray-400 mt-1">Ingresos − Gastos</p>
            </div>

            <!-- Clientes Activos -->
            <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-indigo-500">
                <h3 class="text-gray-500 text-xs font-bold uppercase">Clientes Activos</h3>
                <p class="text-2xl font-black text-gray-800 mt-1">{{ $activeClients }} / {{ $totalClients }}</p>
                <p class="text-xs text-gray-400 mt-1">Alertas de mora: {{ $latePayments->count() }}</p>
            </div>
        </div>

        <!-- Tablas de Desglose por Usuario -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <!-- Ingresos Recaudados por Usuario -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-green-50 flex items-center justify-between">
                    <h3 class="font-bold text-green-800 text-base">💰 Ingresos Recaudados por Usuario</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50 text-xs font-medium text-gray-500 uppercase">
                            <tr>
                                <th class="px-6 py-3 text-left">Usuario / Cobrador</th>
                                <th class="px-6 py-3 text-center">Cant. Pagos</th>
                                <th class="px-6 py-3 text-right">Total Cobrado</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 text-sm">
                            @forelse($incomeByUser ?? [] as $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 font-medium text-gray-900">
                                        {{ $item->user->name ?? 'Usuario no asignado' }}
                                    </td>
                                    <td class="px-6 py-4 text-center text-gray-600">{{ $item->count }}</td>
                                    <td class="px-6 py-4 text-right font-bold text-green-600">${{ number_format($item->total, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-center text-gray-400 text-xs">
                                        No hay cobros registrados asociados a usuarios.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Gastos Registrados por Usuario -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-red-50 flex items-center justify-between">
                    
                    <x-nav-link :href="route('expenses.index')" :active="request()->routeIs('expenses.*')">
                        <h3 class="font-bold text-red-800 text-base">💸 Gastos Registrados por Usuario</h3>
                    </x-nav-link>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50 text-xs font-medium text-gray-500 uppercase">
                            <tr>
                                <th class="px-6 py-3 text-left">Usuario</th>
                                <th class="px-6 py-3 text-center">Registros</th>
                                <th class="px-6 py-3 text-right">Total Gastado</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 text-sm">
                            @forelse($expensesByUser ?? [] as $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 font-medium text-gray-900">
                                        {{ $item->user->name ?? 'Usuario no asignado' }}
                                    </td>
                                    <td class="px-6 py-4 text-center text-gray-600">{{ $item->count }}</td>
                                    <td class="px-6 py-4 text-right font-bold text-red-600">${{ number_format($item->total, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-center text-gray-400 text-xs">
                                        No hay gastos registrados asociados a usuarios.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <!-- Tabla de Alertas de Mora -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200 bg-red-50">
                <h2 class="text-lg font-bold text-red-700">⚠️ Clientes con Pagos Atrasados ({{ $latePayments->count() }})</h2>
            </div>
            
            @if($latePayments->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50 text-xs font-medium text-gray-500 uppercase">
                            <tr>
                                <th class="px-6 py-3 text-left">Cliente</th>
                                <th class="px-6 py-3 text-left">Plan</th>
                                <th class="px-6 py-3 text-left">Vencimiento</th>
                                <th class="px-6 py-3 text-right">Deuda</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 text-sm">
                            @foreach($latePayments as $payment)
                            <tr class="hover:bg-red-50 transition duration-150">
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $payment->client->full_name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-gray-500">{{ $payment->client->plan->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-red-600 font-bold">{{ $payment->due_date }}</td>
                                <td class="px-6 py-4 text-right font-bold text-gray-900">${{ number_format($payment->amount, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-6 text-center text-gray-500">
                    No hay clientes con pagos atrasados por el momento. ¡Excelente!
                </div>
            @endif
        </div>

    </div>
</x-app-layout>