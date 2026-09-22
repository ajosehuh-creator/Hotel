<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class HabitacionController extends Controller
{
    private $url;
    private $headers;

    public function __construct()
    {
        // URL principal para habitaciones
        $this->url = env('SUPABASE_URL') . '/rest/v1/habitaciones';
        $this->headers = [
            'apikey' => env('SUPABASE_SECRET_KEY'),
            'Authorization' => 'Bearer ' . env('SUPABASE_SECRET_KEY'),
            'Content-Type' => 'application/json',
            'Prefer' => 'return=representation'
        ];
    }

    public function index()
    {
        // 1. Traer habitaciones (con el JOIN automático al tipo de habitación)
        $resHabitaciones = Http::withHeaders($this->headers)->get($this->url, [
            'select' => '*,tipos_habitacion(*)'
        ]);
        $habitaciones = $resHabitaciones->successful() ? $resHabitaciones->json() : [];

        // 2. Traer los Tipos de Habitación (solo id y nombre) para llenar el <select> en los modales
        $urlTipos = env('SUPABASE_URL') . '/rest/v1/tipos_habitacion';
        $resTipos = Http::withHeaders($this->headers)->get($urlTipos, [
            'select' => 'id,nombre'
        ]);
        $tipos = $resTipos->successful() ? $resTipos->json() : [];

        // Retornar la vista pasando ambas variables
        return view('habitaciones.index', compact('habitaciones', 'tipos'));
    }

    public function store(Request $request)
    {
        $datos = $request->only(['numero', 'tipo_id', 'precio_noche', 'estado']);
        Http::withHeaders($this->headers)->post($this->url, $datos);

        return redirect()->route('habitaciones.index')->with('success', 'Habitación registrada exitosamente.');
    }

    public function update(Request $request, string $id)
    {
        $datos = $request->only(['numero', 'tipo_id', 'precio_noche', 'estado']);
        Http::withHeaders($this->headers)->patch($this->url . '?id=eq.' . $id, $datos);

        return redirect()->route('habitaciones.index')->with('success', 'Habitación actualizada correctamente.');
    }

    public function destroy(string $id)
    {
        Http::withHeaders($this->headers)->delete($this->url . '?id=eq.' . $id);
        
        return redirect()->route('habitaciones.index')->with('success', 'Habitación eliminada.');
    }
}