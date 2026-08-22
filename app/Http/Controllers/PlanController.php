<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;


class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::all();
        return view('plans.index', compact('plans'));
    }

    public function create()
    {
        return view('plans.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'download_speed' => 'required|integer',
            'upload_speed' => 'required|integer',
            'price' => 'required|numeric',
        ]);

        Plan::create($validated);

        return redirect()->route('plans.index')->with('success', 'Plan creado exitosamente.');
    }

    public function edit(Plan $plan)
    {
        return view('plans.edit', compact('plan'));
    }

    public function update(Request $request, Plan $plan)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'download_speed' => 'required|integer',
            'upload_speed' => 'required|integer',
            'price' => 'required|numeric',
        ]);

        $plan->update($validated);

        return redirect()->route('plans.index')->with('success', 'Plan actualizado.');
    }

    public function destroy(Plan $plan)
    {
        // Buena práctica: evitar borrar un plan si hay clientes usándolo
        if ($plan->clients()->count() > 0) {
            return redirect()->route('plans.index')->with('error', 'No puedes eliminar un plan que tiene clientes activos.');
        }

        $plan->delete();
        return redirect()->route('plans.index')->with('success', 'Plan eliminado.');
    }
}