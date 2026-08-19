<?php
namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Client;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Ingresos del mes actual
        $monthlyIncome = Payment::whereMonth('paid_at', $currentMonth)
                                ->whereYear('paid_at', $currentYear)
                                ->where('status', 'paid')
                                ->sum('amount');

        // Alertas de Demoras
        $latePayments = Payment::with(['client', 'client.plan'])
                               ->where('status', '!=', 'paid')
                               ->where('due_date', '<', Carbon::today())
                               ->get();

        // Estadísticas Generales
        $totalClients = Client::count();
        $activeClients = Client::where('status', 'active')->count();

        return view('dashboard', compact('monthlyIncome', 'latePayments', 'totalClients', 'activeClients'));
    }
}