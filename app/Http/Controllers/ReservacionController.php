<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class ReservacionController extends Controller
{
    private $headers;
    private $baseUrl;

    public function __construct()
    {
        $this->baseUrl = env('SUPABASE_URL') . '/rest/v1/';
        $this->headers = [
            'apikey' => env('SUPABASE_SECRET_KEY'),
            'Authorization' => 'Bearer ' . env('SUPABASE_SECRET_KEY'),
            'Content-Type' => 'application/json',
            'Prefer' => 'return=representation'
        ];
    }

    public function index()
    {
        // 1. Obtener datos de Supabase usando cURL nativo de Laravel
        $resReservaciones = Http::withHeaders($this->headers)->get($this->baseUrl . 'reservaciones', ['select' => '*']);
        $resHabitaciones = Http::withHeaders($this->headers)->get($this->baseUrl . 'habitaciones', ['select' => '*']);
        $resClientes = Http::withHeaders($this->headers)->get($this->baseUrl . 'clientes', ['select' => '*']);
        $resTipos = Http::withHeaders($this->headers)->get($this->baseUrl . 'tipos_habitacion', ['select' => '*']);

        // Convertir respuestas a Objetos
        $reservacionesData = $resReservaciones->successful() ? $resReservaciones->object() : [];
        $habitacionesData = $resHabitaciones->successful() ? $resHabitaciones->object() : [];
        $clientesData = $resClientes->successful() ? $resClientes->object() : [];
        $tiposData = $resTipos->successful() ? $resTipos->object() : [];

        // 2. Mapeo de relaciones (Simulando los JOINs)
        $tiposDict = [];
        foreach ($tiposData as $t) { $tiposDict[$t->id] = $t->nombre; }

        $clientesDict = [];
        foreach ($clientesData as $c) { $clientesDict[$c->id] = $c; }

        $habitacionesDict = [];
        $habitacionesList = []; 
        foreach ($habitacionesData as $h) {
            $h->tipo_habitacion = $tiposDict[$h->tipo_id] ?? 'Sin tipo';
            $habitacionesDict[$h->id] = $h;
            $habitacionesList[] = $h; // Para usar en los selects
        }

        $reservaciones = [];
        foreach ($reservacionesData as $r) {
            $hab = $habitacionesDict[$r->habitacion_id] ?? null;
            $cli = $clientesDict[$r->cliente_id] ?? null;

            $r->habitacion_numero = $hab ? $hab->numero : 'N/A';
            $r->precio_noche = $hab ? $hab->precio_noche : 0;
            $r->tipo_habitacion = $hab ? $hab->tipo_habitacion : 'N/A';
            $r->cliente_nombre = $cli ? $cli->nombre : 'N/A';
            $r->cliente_correo = $cli ? $cli->correo : '';

            $reservaciones[] = $r;
        }

        // Ordenar colecciones para la vista
        usort($reservaciones, function($a, $b) { return $b->id <=> $a->id; });
        usort($clientesData, function($a, $b) { return strcmp($a->nombre, $b->nombre); });
        usort($habitacionesList, function($a, $b) { return strcmp($a->numero, $b->numero); });

        return view('reservaciones.index', [
            'reservaciones' => collect($reservaciones),
            'habitaciones' => $habitacionesList,
            'clientes' => $clientesData
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'habitacion_id' => 'required|integer',
            'cliente_id' => 'required|integer',
            'fecha_entrada' => 'required|date',
            'fecha_salida' => 'required|date|after:fecha_entrada',
            'estado' => 'required|string|max:50',
        ], [
            'fecha_salida.after' => 'La fecha de salida debe ser posterior a la fecha de entrada.',
            // Agrega el resto de tus mensajes personalizados si lo deseas
        ]);

        if ($validator->fails()) {
            return redirect()->route('reservaciones.index')->withErrors($validator, 'crear')->withInput()->with('abrir_modal_crear', true);
        }

        // 1. Validar que la habitación no esté ocupada en esas fechas
        $ocupada = $this->verificarOcupacion($request->habitacion_id, $request->fecha_entrada, $request->fecha_salida);

        if ($ocupada) {
            return redirect()->route('reservaciones.index')->withInput()->with('abrir_modal_crear', true)
                ->with('error', 'La habitación ya tiene una reservación dentro de esas fechas.');
        }

        // 2. Insertar vía API
        $datos = $request->only(['habitacion_id', 'cliente_id', 'fecha_entrada', 'fecha_salida', 'estado']);
        Http::withHeaders($this->headers)->post($this->baseUrl . 'reservaciones', $datos);

        return redirect()->route('reservaciones.index')->with('success', 'La reservación se registró correctamente.');
    }

    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'habitacion_id' => 'required|integer',
            'cliente_id' => 'required|integer',
            'fecha_entrada' => 'required|date',
            'fecha_salida' => 'required|date|after:fecha_entrada',
            'estado' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return redirect()->route('reservaciones.index')->withErrors($validator, 'editar_' . $id)->withInput()->with('abrir_modal_editar', $id);
        }

        // 1. Validar que la habitación no esté ocupada (ignorando esta misma reservación)
        $ocupada = $this->verificarOcupacion($request->habitacion_id, $request->fecha_entrada, $request->fecha_salida, $id);

        if ($ocupada) {
            return redirect()->route('reservaciones.index')->withInput()->with('abrir_modal_editar', $id)
                ->with('error', 'La habitación ya está reservada dentro de esas fechas.');
        }

        // 2. Actualizar vía API
        $datos = $request->only(['habitacion_id', 'cliente_id', 'fecha_entrada', 'fecha_salida', 'estado']);
        Http::withHeaders($this->headers)->patch($this->baseUrl . 'reservaciones?id=eq.' . $id, $datos);

        return redirect()->route('reservaciones.index')->with('success', 'La reservación se actualizó correctamente.');
    }

    public function destroy(string $id)
    {
        Http::withHeaders($this->headers)->delete($this->baseUrl . 'reservaciones?id=eq.' . $id);
        return redirect()->route('reservaciones.index')->with('success', 'La reservación se eliminó correctamente.');
    }

    // Función auxiliar para comprobar empalme de fechas
    private function verificarOcupacion($habitacionId, $entrada, $salida, $ignorarId = null)
    {
        $res = Http::withHeaders($this->headers)->get($this->baseUrl . 'reservaciones', [
            'habitacion_id' => 'eq.' . $habitacionId,
            'select' => '*'
        ]);
        
        $existentes = $res->successful() ? $res->object() : [];

        foreach ($existentes as $ex) {
            if (strtolower($ex->estado) !== 'cancelada' && $ex->id != $ignorarId) {
                // Condición matemática para detectar empalme de rangos de fechas
                if ($entrada < $ex->fecha_salida && $salida > $ex->fecha_entrada) {
                    return true;
                }
            }
        }
        return false;
    }
}