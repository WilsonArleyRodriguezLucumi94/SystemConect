<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gestión de Gastos') }}
            </h2>
            <a href="{{ route('expenses.create') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-md transition duration-200 text-sm inline-flex items-center gap-2">
                ➕ Registrar Nuevo Gasto
            </a>
        </div>

        @if (session('success'))
            <div class="mt-3 p-3 bg-green-100 border border-green-400 text-green-700 rounded text-sm">
                {{ session('success') }}
            </div>
        @endif
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

        <!-- Tarjeta Resumen de Gastos -->
        <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-red-500 max-w-sm">
            <h3 class="text-gray-500 text-xs font-bold uppercase">Total Gastos Históricos</h3>
            <p class="text-3xl font-black text-red-600 mt-1">${{ number_format($totalExpenses, 2) }}</p>
        </div>

        <!-- Tabla de Gastos -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-200">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50 text-xs font-medium text-gray-500 uppercase">
                        <tr>
                            <th class="px-6 py-3 text-left">Fecha</th>
                            <th class="px-6 py-3 text-left">Descripción</th>
                            <th class="px-6 py-3 text-left">Categoría</th>
                            <th class="px-6 py-3 text-left">Registrado Por</th>
                            <th class="px-6 py-3 text-right">Monto</th>
                            <th class="px-6 py-3 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 text-sm">
                        @forelse($expenses as $expense)
                            <tr class="hover:bg-gray-50 transition duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-gray-600 font-medium">
                                    {{ \Carbon\Carbon::parse($expense->expense_date)->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 font-semibold text-gray-900">
                                    {{ $expense->description }}
                                </td>
                                <td class="px-6 py-4 text-gray-500">
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ $expense->category ?? 'General' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-600">
                                    {{ $expense->user->name ?? 'Sistema' }}
                                </td>
                                <td class="px-6 py-4 text-right font-bold text-red-600">
                                    ${{ number_format($expense->amount, 2) }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('expenses.edit', $expense) }}" class="text-indigo-600 hover:text-indigo-900 font-semibold text-xs">
                                            Editar
                                        </a>
                                        <form action="{{ route('expenses.destroy', $expense) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar este gasto?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 font-semibold text-xs">
                                                Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-400">
                                    No hay gastos registrados aún.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($expenses->hasPages())
                <div class="p-4 bg-gray-50 border-t border-gray-200">
                    {{ $expenses->links() }}
                </div>
            @endif
        </div>

    </div>
</x-app-layout>