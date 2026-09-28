<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    public function index()
    {
        $baseUrl = env('SUPABASE_URL') . '/rest/v1/';
        $headers = [
            'apikey' => env('SUPABASE_SECRET_KEY'),
            'Authorization' => 'Bearer ' . env('SUPABASE_SECRET_KEY'),
            'Content-Type' => 'application/json'
        ];

        // Hacer las peticiones a Supabase
        $resHabitaciones = Http::withoutVerifying()->withHeaders($headers)->get($baseUrl . 'habitaciones', ['select' => 'id']);
        $resClientes = Http::withoutVerifying()->withHeaders($headers)->get($baseUrl . 'clientes', ['select' => 'id']);
        $resReservaciones = Http::withoutVerifying()->withHeaders($headers)->get($baseUrl . 'reservaciones', ['select' => 'id,estado']);

        // Convertir a arreglos
        $habitaciones = $resHabitaciones->successful() ? $resHabitaciones->json() : [];
        $clientes = $resClientes->successful() ? $resClientes->json() : [];
        $reservaciones = $resReservaciones->successful() ? $resReservaciones->json() : [];

        // Calcular las estadísticas
        $totalHabitaciones = count($habitaciones);
        $totalClientes = count($clientes);
        $totalReservaciones = count($reservaciones);
        
        $reservacionesActivas = 0;
        foreach ($reservaciones as $res) {
            if (in_array(strtolower($res['estado']), ['pendiente', 'confirmada'])) {
                $reservacionesActivas++;
            }
        }

        return view('hotel_dashboard', compact(
            'totalHabitaciones', 
            'totalClientes', 
            'totalReservaciones', 
            'reservacionesActivas'
        ));
    }
}