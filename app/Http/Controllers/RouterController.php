<?php

namespace App\Http\Controllers;

use App\Models\Router;
use Illuminate\Http\Request;

class RouterController extends Controller
{
    public function index()
    {
        $routers = Router::latest()->paginate(10);
        return view('routers.index', compact('routers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'ip_address' => 'required|string|max:255', // Permite formato IP:PUERTO (ej: 18.220.10.5:8001)
            'username'   => 'required|string|max:255',
            'password'   => 'required|string|max:255',
        ]);

        Router::create($request->only(['name', 'ip_address', 'username', 'password']));

        return redirect()->back()->with('success', 'Router registrado exitosamente.');
    }

    public function update(Request $request, Router $router)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'ip_address' => 'required|string|max:255',
            'username'   => 'required|string|max:255',
            'password'   => 'required|string|max:255',
        ]);

        $router->update($request->only(['name', 'ip_address', 'username', 'password']));

        return redirect()->back()->with('success', 'Router actualizado exitosamente.');
    }

    public function destroy(Router $router)
    {
        $router->delete();
        return redirect()->back()->with('success', 'Router eliminado correctamente.');
    }
}