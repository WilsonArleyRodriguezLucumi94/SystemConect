<?php
namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Client;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Expense;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // 1. Totales Históricos Globales
        $totalIncome = Payment::where('status', 'paid')->sum('amount');
        $totalExpenses = Expense::sum('amount');
        $totalNetBalance = $totalIncome - $totalExpenses;

        // 2. Totales del Mes Actual
        $monthlyIncome = Payment::whereMonth('paid_at', $currentMonth)
                                ->whereYear('paid_at', $currentYear)
                                ->where('status', 'paid')
                                ->sum('amount');

        $monthlyExpenses = Expense::whereMonth('expense_date', $currentMonth)
                                  ->whereYear('expense_date', $currentYear)
                                  ->sum('amount');

        $monthlyNetBalance = $monthlyIncome - $monthlyExpenses;

        // 3. Ingresos agrupados por Usuario / Cobrador
        $incomeByUser = Payment::with('user')
            ->where('status', 'paid')
            ->whereNotNull('user_id')
            ->select('user_id', DB::raw('SUM(amount) as total'), DB::raw('COUNT(id) as count'))
            ->groupBy('user_id')
            ->get();

        // 4. Gastos agrupados por Usuario que registró
        $expensesByUser = Expense::with('user')
            ->whereNotNull('user_id')
            ->select('user_id', DB::raw('SUM(amount) as total'), DB::raw('COUNT(id) as count'))
            ->groupBy('user_id')
            ->get();

        // 5. Alertas de Demoras / Morosos
        $latePayments = Payment::with(['client', 'client.plan'])
                               ->where('status', '!=', 'paid')
                               ->where('due_date', '<', Carbon::today())
                               ->get();

        // 6. Métricas de Clientes
        $totalClients = Client::count();
        $activeClients = Client::where('status', 'active')->count();

        return view('dashboard', compact(
            'totalIncome',
            'totalExpenses',
            'totalNetBalance',
            'monthlyIncome',
            'monthlyExpenses',
            'monthlyNetBalance',
            'incomeByUser',
            'expensesByUser',
            'latePayments',
            'totalClients',
            'activeClients'
        ));
    }

    public function generateDailyInvoices()
    {
        try {
            // Ejecuta el comando Artisan
            Artisan::call('app:check-daily-invoices');

            return redirect()->back()->with('success', 'Cobros del día procesados correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al ejecutar el proceso: ' . $e->getMessage());
        }
    }
}