<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TipoHabitacionController extends Controller
{
    private $url;
    private $headers;

    public function __construct()
    {
        $this->url = env('SUPABASE_URL') . '/rest/v1/tipos_habitacion';
        $this->headers = [
            'apikey' => env('SUPABASE_SECRET_KEY'),
            'Authorization' => 'Bearer ' . env('SUPABASE_SECRET_KEY'),
            'Content-Type' => 'application/json',
            'Prefer' => 'return=representation'
        ];
    }

    public function index()
    {
        // Traemos los datos de Supabase
        $response = Http::withHeaders($this->headers)->get($this->url, ['select' => '*']);
        $tipos = $response->successful() ? $response->json() : [];

        // Retornamos la vista enviando la variable $tipos
        return view('tipos_habitacion.index', compact('tipos'));
    }

    public function store(Request $request)
    {
        $datos = $request->only(['nombre', 'descripcion', 'capacidad_personas']);
        Http::withHeaders($this->headers)->post($this->url, $datos);

        // Redirigimos a la vista con un mensaje de éxito
        return redirect()->route('tipos-habitacion.index')->with('success', 'Tipo de habitación agregado.');
    }

    public function update(Request $request, string $id)
    {
        $datos = $request->only(['nombre', 'descripcion', 'capacidad_personas']);
        Http::withHeaders($this->headers)->patch($this->url . '?id=eq.' . $id, $datos);

        return redirect()->route('tipos-habitacion.index')->with('success', 'Actualizado correctamente.');
    }

    public function destroy(string $id)
    {
        Http::withHeaders($this->headers)->delete($this->url . '?id=eq.' . $id);
        
        return redirect()->route('tipos-habitacion.index')->with('success', 'Eliminado correctamente.');
    }
}