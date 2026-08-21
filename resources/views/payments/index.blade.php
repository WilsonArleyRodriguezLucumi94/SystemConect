<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestión de Pagos') }}
        </h2>
    </x-slot>

    <!-- Contenedor Alpine.js para gestionar el estado del Modal -->
    <div x-data="{ openModal: false, paymentId: null, clientName: '', amount: '' }" class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

        @if (session('success'))
            <div class="p-4 bg-green-100 border border-green-400 text-green-700 rounded-md text-sm">
                {{ session('success') }}
            </div>
        @endif

        <!-- Tabla de Pagos -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-200">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50 text-xs font-medium text-gray-500 uppercase">
                        <tr>
                            <th class="px-6 py-3 text-left">Cliente</th>
                            <th class="px-6 py-3 text-left">Vencimiento</th>
                            <th class="px-6 py-3 text-right">Monto</th>
                            <th class="px-6 py-3 text-center">Estado</th>
                            <th class="px-6 py-3 text-left">Cuenta / Método</th>
                            <th class="px-6 py-3 text-left">Cobrado por</th>
                            <th class="px-6 py-3 text-center">Comprobante</th>
                            <th class="px-6 py-3 text-center">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 text-sm">
                        @forelse($payments as $payment)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 font-medium text-gray-900">
                                    {{ $payment->client->full_name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 text-gray-600">
                                    {{ $payment->due_date }}
                                </td>
                                <td class="px-6 py-4 text-right font-bold text-gray-800">
                                    ${{ number_format($payment->amount, 2) }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($payment->status === 'paid')
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                            Pagado
                                        </span>
                                    @else
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                                            Pendiente
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-gray-600">
                                    {{ $payment->payment_method ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-gray-600">
                                    {{ $payment->user->name ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($payment->proof_image)
                                        <a href="{{ asset('storage/' . $payment->proof_image) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900 font-semibold text-xs underline">
                                            Ver Foto
                                        </a>
                                    @else
                                        <span class="text-gray-400 text-xs">Sin foto</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($payment->status !== 'paid')
                                        <button 
                                            @click="
                                                openModal = true; 
                                                paymentId = {{ $payment->id }}; 
                                                clientName = '{{ addslashes($payment->client->full_name ?? '') }}'; 
                                                amount = '{{ number_format($payment->amount, 2) }}';
                                            "
                                            class="px-3 py-1 bg-green-600 hover:bg-green-700 text-white font-semibold rounded text-xs shadow-sm transition">
                                            Registrar Pago
                                        </button>
                                    @else
                                        <span class="text-xs text-gray-400">Completado</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-8 text-center text-gray-400">
                                    No hay registros de pagos disponibles.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($payments->hasPages())
                <div class="p-4 bg-gray-50 border-t border-gray-200">
                    {{ $payments->links() }}
                </div>
            @endif
        </div>

        <!-- MODAL REGISTRAR PAGO -->
        <div 
            x-show="openModal" 
            x-cloak
            class="fixed inset-0 z-50 overflow-y-auto bg-gray-900 bg-opacity-50 flex items-center justify-center p-4"
        >
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full overflow-hidden transform transition-all">
                
                <!-- Encabezado Modal -->
                <div class="bg-indigo-600 px-6 py-4 flex justify-between items-center text-white">
                    <h3 class="font-bold text-lg">Registrar Ingreso de Pago</h3>
                    <button @click="openModal = false" class="text-indigo-200 hover:text-white font-bold text-xl">&times;</button>
                </div>

                <!-- Formulario Modal -->
                <form :action="'/payments/' + paymentId + '/pay'" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                    @csrf

                    <div>
                        <p class="text-sm text-gray-500">Cliente: <strong x-text="clientName" class="text-gray-800"></strong></p>
                        <p class="text-sm text-gray-500">Monto a Cobrar: <strong x-text="'$' + amount" class="text-green-600"></strong></p>
                    </div>

                    <!-- Usuario que recibe el dinero -->
                    <div>
                        <label for="user_id" class="block text-sm font-medium text-gray-700">Usuario / Cobrador que recibe *</label>
                        <select name="user_id" id="user_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" required>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ auth()->id() == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Cuenta o Método de Ingreso -->
                    <div>
                        <label for="payment_method" class="block text-sm font-medium text-gray-700">Cuenta / Método de Pago *</label>
                        <select name="payment_method" id="payment_method" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" required>
                            <option value="Efectivo">Efectivo en Caja</option>
                            <option value="Nequi">Nequi</option>
                            <option value="Daviplata">Daviplata</option>
                            <option value="Bancolombia">Cuenta Bancolombia</option>
                            <option value="Transferencia Bancaria">Otra Transferencia Bancaria</option>
                        </select>
                    </div>

                    <!-- Foto / Comprobante -->
                    <div>
                        <label for="proof_image" class="block text-sm font-medium text-gray-700">Comprobante de Pago (Opcional)</label>
                        <input type="file" name="proof_image" id="proof_image" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 border border-gray-300 rounded-md shadow-sm p-1">
                    </div>

                    <!-- Botones de Acción -->
                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                        <button type="button" @click="openModal = false" class="px-4 py-2 bg-gray-200 text-gray-700 font-semibold rounded-md hover:bg-gray-300 text-sm">
                            Cancelar
                        </button>
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white font-semibold rounded-md hover:bg-green-700 text-sm shadow">
                            Confirmar Pago
                        </button>
                    </div>

                </form>

            </div>
        </div>

    </div>
</x-app-layout>