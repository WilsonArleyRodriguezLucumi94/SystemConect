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

    return view('payments.index', compact('payments', 'users'));
}

    public function pay(Request $request, Payment $payment)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'user_id' => 'required|exists:users,id',
            'payment_method' => 'required|string',
            'next_due_date' => 'required|date',
            'proof_image' => 'nullable|image|max:2048',
        ]);

        // Guardar la imagen si fue adjuntada
        $proofPath = null;
        if ($request->hasFile('proof_image')) {
            $proofPath = $request->file('proof_image')->store('proofs', 'public');
        }

        // Actualizar el registro del pago actual con el nuevo monto ingresado
        $payment->update([
            'amount' => $request->amount,
            'status' => 'paid',
            'user_id' => $request->user_id,
            'payment_method' => $request->payment_method,
            'proof_image' => $proofPath ?? $payment->proof_image,
        ]);

        // Actualizar la fecha de próximo vencimiento en el cliente
        $payment->client->update([
            'next_due_date' => $request->next_due_date,
        ]);

        return redirect()->route('payments.index')->with('success', 'Pago registrado correctamente.');
    }
}