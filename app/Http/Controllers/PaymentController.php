<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\User;
use App\Services\RouterService;
use App\Services\MikrotikService; // <-- AGREGAR ESTA LÍNEA
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    public function index(Request $request)
{
    $search = $request->input('search');

    $payments = Payment::with(['client', 'user'])
        ->when($search, function ($query, $search) {
            $query->whereHas('client', function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%");
            });
        })
        ->latest()
        ->paginate(15)
        ->withQueryString();

    $users = User::all();

    //dd($users->due_date);

    return view('payments.index', compact('payments', 'users'));
}

    public function pay(Request $request, Payment $payment, MikrotikService $mikrotikService)
        {
            $request->validate([
                'amount' => 'required|numeric|min:0',
                'user_id' => 'required|exists:users,id',
                'payment_method' => 'required|string',
                'next_due_date' => 'required|date',
                'proof_image' => 'nullable|image|max:2048',
            ]);

            // 1. Guardar la imagen si fue adjuntada
            $proofPath = $payment->proof_image;
            if ($request->hasFile('proof_image')) {
                $proofPath = $request->file('proof_image')->store('proofs', 'public');
            }

            // 2. Marcar la factura ACTUAL como PAGADA (conserva su fecha de vencimiento original)
            $payment->update([
                'amount' => $request->amount,
                'status' => 'paid',
                'paid_at' => now(),
                'user_id' => $request->user_id,
                'payment_method' => $request->payment_method,
                'proof_image' => $proofPath,
            ]);

            $client = $payment->client;

            // 3. Actualizar la próxima fecha de vencimiento y estado en el Cliente
            $client->update([
                'next_due_date' => $request->next_due_date,
                'status' => 'active',
            ]);

            // 4. Crear el NUEVO registro de cobro pendiente para el próximo ciclo
            Payment::create([
                'client_id' => $client->id,
                'amount' => $client->plan?->price ?? $request->amount,
                'due_date' => $request->next_due_date,
                'status' => 'pending',
            ]);

            // 5. Reactivar servicio en MikroTik (Remover de la lista SUSPENDIDOS)
            $mikrotikService->reactivateClient($client);

            return redirect()->route('payments.index')->with('success', 'Pago registrado con éxito. Se ha generado la nueva factura para el próximo período.');
        }

    
}