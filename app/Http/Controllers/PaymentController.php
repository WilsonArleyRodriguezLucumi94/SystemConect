<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    // Cargar la lista de pagos con los usuarios para el modal
    public function index()
    {
        $payments = Payment::with(['client', 'user'])->latest()->paginate(15);
        $users = User::all(); // Lista de usuarios/cobradores para el select

        return view('payments.index', compact('payments', 'users'));
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

    // Método para procesar el pago cuando se confirma en el modal
    public function pay(Request $request, Payment $payment)
    {
        $request->validate([
            'user_id'        => 'required|exists:users,id',
            'payment_method' => 'required|string|max:100',
            'proof_image'    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096', // Máximo 4MB
        ]);

        $proofPath = $payment->proof_image;

        // Guardar la imagen si fue subida
        if ($request->hasFile('proof_image')) {
            // Eliminar imagen anterior si existía
            if ($proofPath && Storage::disk('public')->exists($proofPath)) {
                Storage::disk('public')->delete($proofPath);
            }
            $proofPath = $request->file('proof_image')->store('receipts', 'public');
        }

        // Marcar el pago como pagado y registrar detalles
        $payment->update([
            'status'         => 'paid',
            'paid_at'        => now(),
            'user_id'        => $request->user_id,
            'payment_method' => $request->payment_method,
            'proof_image'    => $proofPath,
        ]);

        return redirect()->back()->with('success', '¡Pago registrado exitosamente!');
    }
}