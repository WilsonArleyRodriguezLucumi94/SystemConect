<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gestión de Pagos') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Alertas de éxito o error -->
            @if(session('success'))
                <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Tabla de Pagos -->
            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Cliente</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Monto</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Vencimiento</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($payments as $payment)
                        <tr class="hover:bg-gray-50">
                            <!-- Cliente y Plan -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-gray-900">{{ $payment->client->full_name }}</div>
                                <div class="text-xs text-gray-500">{{ $payment->client->plan->name ?? 'Sin Plan' }}</div>
                            </td>
                            
                            <!-- Monto a pagar -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-bold">
                                ${{ number_format($payment->amount, 2) }}
                            </td>
                            
                            <!-- Fecha de Vencimiento -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium 
                                {{ ($payment->status != 'paid' && \Carbon\Carbon::parse($payment->due_date)->isPast()) ? 'text-red-600' : 'text-gray-500' }}">
                                {{ \Carbon\Carbon::parse($payment->due_date)->format('d/m/Y') }}
                            </td>
                            
                            <!-- Estado (Badges) -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($payment->status == 'paid')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Pagado
                                    </span>
                                    <div class="text-xs text-gray-400 mt-1">el {{ \Carbon\Carbon::parse($payment->paid_at)->format('d/m/Y') }}</div>
                                @elseif($payment->status == 'late' || ($payment->status == 'pending' && \Carbon\Carbon::parse($payment->due_date)->isPast()))
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Atrasado
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Pendiente
                                    </span>
                                @endif
                            </td>
                            
                            <!-- Botón de Acción -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                @if($payment->status != 'paid')
                                    <form action="{{ route('payments.pay', $payment) }}" method="POST" onsubmit="return confirm('¿Confirmar que el cliente realizó el pago de ${{ number_format($payment->amount, 2) }}?');">
                                        @csrf
                                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-1 px-3 rounded text-xs shadow">
                                            Registrar Pago
                                        </button>
                                    </form>
                                @else
                                    <span class="text-gray-400 text-xs italic">Sin acciones</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                No hay facturas o pagos registrados en el sistema.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>