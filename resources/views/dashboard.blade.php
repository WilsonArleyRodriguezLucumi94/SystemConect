<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Panel de Control ISP') }}
        </h2>

        <!-- Botón para ejecutar la acción -->
        <form action="{{ route('invoices.generate-daily') }}" method="POST" onsubmit="return confirm('¿Deseas procesar los cobros pendientes del día de hoy?');">
            @csrf
            <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md transition duration-200">
                ⚡ Generar Cobros del Día
            </button>
        </form>

        <!-- Mensaje de confirmación -->
        @if (session('success'))
            <div class="mt-3 p-3 bg-green-100 border border-green-400 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mt-3 p-3 bg-red-100 border border-red-400 text-red-700 rounded">
                {{ session('error') }}
            </div>
        @endif
    </x-slot>

    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <!-- Tarjetas de Resumen -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-green-500">
                <h3 class="text-gray-500 text-sm font-bold uppercase">Ingresos del Mes</h3>
                <p class="text-3xl font-black text-gray-800">${{ number_format($monthlyIncome, 2) }}</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-blue-500">
                <h3 class="text-gray-500 text-sm font-bold uppercase">Clientes Activos</h3>
                <p class="text-3xl font-black text-gray-800">{{ $activeClients }} / {{ $totalClients }}</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-red-500">
                <h3 class="text-gray-500 text-sm font-bold uppercase">Alertas de Mora</h3>
                <p class="text-3xl font-black text-red-600">{{ $latePayments->count() }}</p>
            </div>
        </div>

        <!-- Tabla de Alertas -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-red-50">
                <h2 class="text-lg font-bold text-red-700">⚠️ Clientes con Pagos Atrasados</h2>
            </div>
            
            @if($latePayments->count() > 0)
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cliente</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Plan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vencimiento</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Deuda</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($latePayments as $payment)
                        <tr>
                            <td class="px-6 py-4 font-medium">{{ $payment->client->full_name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $payment->client->plan->name }}</td>
                            <td class="px-6 py-4 text-sm text-red-600 font-bold">{{ $payment->due_date }}</td>
                            <td class="px-6 py-4 text-sm">${{ number_format($payment->amount, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="p-6 text-center text-gray-500">
                    No hay clientes con pagos atrasados por el momento. ¡Excelente!
                </div>
            @endif
        </div>

    </div>
</x-app-layout>