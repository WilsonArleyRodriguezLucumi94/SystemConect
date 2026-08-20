<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\PaymentController;

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

require __DIR__.'/auth.php';
