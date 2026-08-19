<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Plan;
use App\Models\Payment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ClientController extends Controller
{
    public function index()
    {
        // Cargamos los clientes junto con la información de su plan (Eager Loading)
        $clients = Client::with('plan')->get();
        return view('clients.index', compact('clients'));
    }

    public function create()
    {
        $plans = Plan::all(); // Necesitamos los planes para el <select> en la vista
        return view('clients.create', compact('plans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'document_number' => 'required|unique:clients',
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string',
            'address' => 'required|string',
            'ip_address' => 'nullable|ip|unique:clients',
            'plan_id' => 'required|exists:plans,id',
            'billing_day' => 'required|date',
        ]);

        // 1. Creamos al cliente
        $client = Client::create($validated);

        // 2. Generamos su primera factura automáticamente (opcional pero muy útil)
        Payment::create([
            'client_id' => $client->id,
            'amount' => $client->plan->price,
            'due_date' => $client->billing_day,
            'status' => 'pending'
        ]);

        return redirect()->route('clients.index')->with('success', 'Cliente registrado y primera factura generada.');
    }

    public function edit(Client $client)
    {
        $plans = Plan::all();
        return view('clients.edit', compact('client', 'plans'));
    }

    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'document_number' => 'required|unique:clients,document_number,' . $client->id,
            'full_name' => 'required|string',
            'phone' => 'required|string',
            'address' => 'required|string',
            'ip_address' => 'nullable|ip|unique:clients,ip_address,' . $client->id,
            'plan_id' => 'required|exists:plans,id',
            'billing_day' => 'required|date',
            'status' => 'required|in:active,suspended'
        ]);

        $client->update($validated);

        return redirect()->route('clients.index')->with('success', 'Datos del cliente actualizados.');
    }

    // El método destroy se puede hacer igual que en planes
}