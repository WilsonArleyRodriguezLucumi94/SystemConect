<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Plan;
use App\Models\Payment;
use App\Models\IpAddress;
use App\Models\Router;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ClientController extends Controller
{
    public function index()
    {
        // Carga de relaciones incluyendo 'router'
        $clients = Client::with(['plan', 'ipAddress', 'router'])->get();
        
        return view('clients.index', compact('clients'));
    }

    public function create()
    {
        $plans = Plan::all();
        $routers = Router::all();
        // Solo traemos las IPs que no están asignadas
        $ips = IpAddress::where('status', 'available')->get(); 
        
        return view('clients.create', compact('plans', 'ips', 'routers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'document_number' => 'required|unique:clients',
            'full_name'       => 'required|string|max:255',
            'phone'           => 'required|string',
            'address'         => 'required|string',
            'ip_address_id'   => 'nullable|exists:ip_addresses,id',
            'router_id'       => 'required|exists:routers,id', // <--- Obligatorio
            'plan_id'         => 'required|exists:plans,id',
            'billing_day'     => 'required|date',
        ]);

        // Parsear la fecha recibida
        $date = Carbon::parse($request->billing_day);

        // Extraer solo el número del día (ej. 21) para la tabla 'clients'
        $validated['billing_day'] = $date->day; 

        $client = Client::create($validated);

        // Si se seleccionó una IP, la marcamos como "asignada"
        if ($client->ip_address_id) {
            IpAddress::find($client->ip_address_id)->update(['status' => 'assigned']);
        }

        // Crear el primer pago pendiente
        Payment::create([
            'client_id' => $client->id,
            'amount'    => $client->plan->price,
            'due_date'  => $date->toDateString(), 
            'status'    => 'pending'
        ]);

        return redirect()->route('clients.index')->with('success', 'Cliente registrado exitosamente.');
    }

    public function edit(Client $client)
    {
        $plans = Plan::all();
        $routers = Router::all();

        return view('clients.edit', compact('client', 'plans', 'routers'));
    }

    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'document_number' => 'required|unique:clients,document_number,' . $client->id,
            'full_name'       => 'required|string',
            'phone'           => 'required|string',
            'address'         => 'required|string',
            'ip_address_id'   => 'nullable|exists:ip_addresses,id',
            'router_id'       => 'required|exists:routers,id', // <--- Actualización de router
            'plan_id'         => 'required|exists:plans,id',
            'status'          => 'required|in:active,suspended'
        ]);

        $client->update($validated);

        return redirect()->route('clients.index')->with('success', 'Datos del cliente actualizados.');
    }
}