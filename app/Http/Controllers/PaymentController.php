<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\User;
use App\Services\RouterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with(['client', 'user'])->latest()->paginate(15);
        $users = User::all();

        return view('payments.index', compact('payments', 'users'));
    }

    public function pay(Request $request, Payment $payment, RouterService $router)
    {
        $request->validate([
            'user_id'        => 'required|exists:users,id',
            'payment_method' => 'required|string|max:100',
            'proof_image'    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
        ]);

        $proofPath = $payment->proof_image;

        // Guardar la imagen si fue subida
        if ($request->hasFile('proof_image')) {
            if ($proofPath && Storage::disk('public')->exists($proofPath)) {
                Storage::disk('public')->delete($proofPath);
            }
            $proofPath = $request->file('proof_image')->store('receipts', 'public');
        }

        // Marcar el pago como pagado
        $payment->update([
            'status'         => 'paid',
            'paid_at'        => now(),
            'user_id'        => $request->user_id,
            'payment_method' => $request->payment_method,
            'proof_image'    => $proofPath,
        ]);

        // Reconexión automática en MikroTik
        $client = $payment->client;

        if ($client) {
            $client->update(['status' => 'active']);

            if ($client->ipAddress) {
                try {
                    $router->activarIp($client->ipAddress->ip_address);
                } catch (\Exception $e) {
                    Log::error("Error al reconectar IP {$client->ipAddress->ip_address} en MikroTik: " . $e->getMessage());
                }
            }
        }

        return redirect()->back()->with('success', '¡Pago registrado y servicio reconectado exitosamente!');
    }
}