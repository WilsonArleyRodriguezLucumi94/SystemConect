<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ExpenseController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::post('/invoices/generate-daily', [DashboardController::class, 'generateDailyInvoices'])
    ->name('invoices.generate-daily');

Route::middleware('auth')->group(function () {
    // Panel Principal
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Rutas para Planes (genera index, create, store, edit, update, destroy automáticamente)
    Route::resource('plans', PlanController::class);

    // Rutas para Clientes
    Route::resource('clients', ClientController::class);

    // Rutas para Pagos
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::post('/payments/{payment}/pay', [PaymentController::class, 'markAsPaid'])->name('payments.pay');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// Rutas para Gastos
Route::middleware(['auth'])->group(function () {
    Route::get('/expenses', [ExpenseController::class, 'index'])->name('expenses.index');
    Route::get('/expenses/create', [ExpenseController::class, 'create'])->name('expenses.create');
    Route::post('/expenses', [ExpenseController::class, 'store'])->name('expenses.store');
    Route::get('/expenses/{expense}/edit', [ExpenseController::class, 'edit'])->name('expenses.edit');
    Route::put('/expenses/{expense}', [ExpenseController::class, 'update'])->name('expenses.update');
    Route::delete('/expenses/{expense}', [ExpenseController::class, 'destroy'])->name('expenses.destroy');
});

require __DIR__.'/auth.php';
