<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class HabitacionController extends Controller
{
    private $url;
    private $headers;
    private $tabla = 'habitaciones'; // El nombre de tu tabla en Supabase

    public function __construct()
    {
        // Usamos la URL y la SECRET_KEY de tu archivo .env
        $this->url = env('SUPABASE_URL') . '/rest/v1/' . $this->tabla;
        
        $this->headers = [
            'apikey' => env('SUPABASE_SECRET_KEY'),
            'Authorization' => 'Bearer ' . env('SUPABASE_SECRET_KEY'),
            'Content-Type' => 'application/json',
            'Prefer' => 'return=representation' 
        ];
    }

    // 1. VER todas las habitaciones
    public function index()
    {
        $response = Http::withHeaders($this->headers)->get($this->url, [
            'select' => '*'
        ]);
        return response()->json($response->json(), $response->status());
    }

    // 2. REGISTRAR una nueva habitación
    public function store(Request $request)
    {
        // Validamos que nos envíen los datos necesarios
        $request->validate([
            'numero' => 'required|string',
            'tipo' => 'required|string',
            'precio' => 'required|numeric'
        ]);

        $datos = $request->only(['numero', 'tipo', 'precio']);
        $response = Http::withHeaders($this->headers)->post($this->url, $datos);

        return response()->json($response->json(), $response->status());
    }

    // 3. ACTUALIZAR o EDITAR una habitación (por ID)
    public function update(Request $request, string $id)
    {
        $datos = $request->only(['numero', 'tipo', 'precio']);
        
        // El filtro ?id=eq.ID es como el "WHERE id = ?" en SQL
        $endpoint = $this->url . '?id=eq.' . $id;
        $response = Http::withHeaders($this->headers)->patch($endpoint, $datos);

        return response()->json($response->json(), $response->status());
    }

    // 4. ELIMINAR una habitación (por ID)
    public function destroy(string $id)
    {
        $endpoint = $this->url . '?id=eq.' . $id;
        $response = Http::withHeaders($this->headers)->delete($endpoint);

        return response()->json(['mensaje' => 'Eliminado correctamente'], $response->status());
    }
}