<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ClienteController extends Controller
{
    private $url;
    private $headers;

    public function __construct()
    {
        $this->url = env('SUPABASE_URL') . '/rest/v1/clientes';
        $this->headers = [
            'apikey' => env('SUPABASE_SECRET_KEY'),
            'Authorization' => 'Bearer ' . env('SUPABASE_SECRET_KEY'),
            'Content-Type' => 'application/json',
            'Prefer' => 'return=representation'
        ];
    }

    public function index()
    {
        $response = Http::withHeaders($this->headers)->get($this->url, [
            'select' => '*'
        ]);
        $clientes = $response->successful() ? $response->json() : [];

        return view('clientes.index', compact('clientes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string',
            'correo' => 'required|email',
            'telefono' => 'nullable|string'
        ]);

        $datos = $request->only(['nombre', 'correo', 'telefono']);
        Http::withHeaders($this->headers)->post($this->url, $datos);

        return redirect()->route('clientes.index')->with('success', 'Cliente registrado exitosamente.');
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'nombre' => 'required|string',
            'correo' => 'required|email',
            'telefono' => 'nullable|string'
        ]);

        $datos = $request->only(['nombre', 'correo', 'telefono']);
        Http::withHeaders($this->headers)->patch($this->url . '?id=eq.' . $id, $datos);

        return redirect()->route('clientes.index')->with('success', 'Cliente actualizado correctamente.');
    }

    public function destroy(string $id)
    {
        Http::withHeaders($this->headers)->delete($this->url . '?id=eq.' . $id);
        
        return redirect()->route('clientes.index')->with('success', 'Cliente eliminado.');
    }
}