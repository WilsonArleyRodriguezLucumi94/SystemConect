<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PaymentController extends Controller
{
    // Muestra todas las facturas (puedes filtrar pendientes, pagadas, etc.)
    public function index()
    {
        // Ordenamos para que las deudas más urgentes salgan primero
        $payments = Payment::with('client.plan')
            ->orderBy('due_date', 'asc')
            ->get();
            
        return view('payments.index', compact('payments'));
    }

    // Registra el pago y reconecta el servicio si estaba cortado
    public function markAsPaid(Payment $payment)
    {
        // 1. Evitar cobrar dos veces
        if ($payment->status === 'paid') {
            return back()->with('error', 'Esta factura ya fue pagada.');
        }

        // 2. Actualizar la factura
        $payment->update([
            'status' => 'paid',
            'paid_at' => Carbon::now(), // Registra fecha y hora exacta del pago
        ]);

        $client = $payment->client;

        // 3. Lógica si el cliente estaba suspendido
        if ($client->status === 'suspended') {
            $client->update(['status' => 'active']);
            
            // TODO: AQUÍ LLAMAREMOS A MIKROTIK MÁS ADELANTE
            // Ejemplo: app(MikrotikService::class)->activateClient($client->ip_address);
        }

        return back()->with('success', 'Pago registrado exitosamente. Servicio activo.');
    }
}