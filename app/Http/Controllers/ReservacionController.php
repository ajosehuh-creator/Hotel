<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ReservacionController extends Controller
{
    /**
     * Mostrar todas las reservaciones
     */
    public function index()
    {
        $reservaciones = DB::table('reservaciones as r')
            ->join('habitaciones as h', 'r.habitacion_id', '=', 'h.id')
            ->join('clientes as c', 'r.cliente_id', '=', 'c.id')
            ->leftJoin('tipos_habitacion as th', 'h.tipo_id', '=', 'th.id')
            ->select(
                'r.id',
                'r.habitacion_id',
                'r.cliente_id',
                'r.fecha_entrada',
                'r.fecha_salida',
                'r.estado',

                'h.numero as habitacion_numero',
                'h.precio_noche',

                'c.nombre as cliente_nombre',
                'c.correo as cliente_correo',

                'th.nombre as tipo_habitacion'
            )
            ->orderBy('r.id', 'desc')
            ->get();

        /**
         * Habitaciones para los selects
         */
        $habitaciones = DB::table('habitaciones as h')
            ->leftJoin('tipos_habitacion as th', 'h.tipo_id', '=', 'th.id')
            ->select(
                'h.id',
                'h.numero',
                'h.precio_noche',
                'h.estado',
                'th.nombre as tipo_habitacion'
            )
            ->orderBy('h.numero')
            ->get();

        /**
         * Clientes para los selects
         */
        $clientes = DB::table('clientes')
            ->select(
                'id',
                'nombre',
                'correo',
                'telefono'
            )
            ->orderBy('nombre')
            ->get();

        return view(
            'reservaciones.index',
            compact(
                'reservaciones',
                'habitaciones',
                'clientes'
            )
        );
    }


    /**
     * Guardar una nueva reservación
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'habitacion_id' => [
                    'required',
                    'integer',
                    'exists:habitaciones,id',
                ],

                'cliente_id' => [
                    'required',
                    'integer',
                    'exists:clientes,id',
                ],

                'fecha_entrada' => [
                    'required',
                    'date',
                ],

                'fecha_salida' => [
                    'required',
                    'date',
                    'after:fecha_entrada',
                ],

                'estado' => [
                    'required',
                    'string',
                    'max:50',
                ],
            ],
            [
                'habitacion_id.required' => 'Debes seleccionar una habitación.',
                'habitacion_id.exists' => 'La habitación seleccionada no existe.',

                'cliente_id.required' => 'Debes seleccionar un cliente.',
                'cliente_id.exists' => 'El cliente seleccionado no existe.',

                'fecha_entrada.required' => 'La fecha de entrada es obligatoria.',
                'fecha_entrada.date' => 'La fecha de entrada no es válida.',

                'fecha_salida.required' => 'La fecha de salida es obligatoria.',
                'fecha_salida.date' => 'La fecha de salida no es válida.',
                'fecha_salida.after' => 'La fecha de salida debe ser posterior a la fecha de entrada.',

                'estado.required' => 'Debes seleccionar un estado.',
            ]
        );

        if ($validator->fails()) {
            return redirect()
                ->route('reservaciones.index')
                ->withErrors($validator, 'crear')
                ->withInput()
                ->with('abrir_modal_crear', true);
        }


        /**
         * Verificar que la habitación no tenga
         * otra reservación en las mismas fechas.
         */
        $ocupada = DB::table('reservaciones')
            ->where('habitacion_id', $request->habitacion_id)
            ->whereRaw('LOWER(estado) != ?', ['cancelada'])
            ->where(function ($query) use ($request) {

                $query->where(
                    'fecha_entrada',
                    '<',
                    $request->fecha_salida
                )
                ->where(
                    'fecha_salida',
                    '>',
                    $request->fecha_entrada
                );

            })
            ->exists();


        if ($ocupada) {

            return redirect()
                ->route('reservaciones.index')
                ->withInput()
                ->with('abrir_modal_crear', true)
                ->with(
                    'error',
                    'La habitación ya tiene una reservación dentro de esas fechas.'
                );
        }


        try {

            DB::table('reservaciones')->insert([
                'habitacion_id' => $request->habitacion_id,
                'cliente_id' => $request->cliente_id,
                'fecha_entrada' => $request->fecha_entrada,
                'fecha_salida' => $request->fecha_salida,
                'estado' => $request->estado,
            ]);

            return redirect()
                ->route('reservaciones.index')
                ->with(
                    'success',
                    'La reservación se registró correctamente.'
                );

        } catch (\Exception $e) {

            return redirect()
                ->route('reservaciones.index')
                ->withInput()
                ->with('abrir_modal_crear', true)
                ->with(
                    'error',
                    'No se pudo registrar la reservación: ' .
                    $e->getMessage()
                );
        }
    }


    /**
     * Actualizar una reservación
     */
    public function update(Request $request, $id)
    {
        $reservacion = DB::table('reservaciones')
            ->where('id', $id)
            ->first();


        if (!$reservacion) {

            return redirect()
                ->route('reservaciones.index')
                ->with(
                    'error',
                    'La reservación que intentas editar no existe.'
                );
        }


        $validator = Validator::make(
            $request->all(),
            [
                'habitacion_id' => [
                    'required',
                    'integer',
                    'exists:habitaciones,id',
                ],

                'cliente_id' => [
                    'required',
                    'integer',
                    'exists:clientes,id',
                ],

                'fecha_entrada' => [
                    'required',
                    'date',
                ],

                'fecha_salida' => [
                    'required',
                    'date',
                    'after:fecha_entrada',
                ],

                'estado' => [
                    'required',
                    'string',
                    'max:50',
                ],
            ],
            [
                'habitacion_id.required' => 'Debes seleccionar una habitación.',
                'habitacion_id.exists' => 'La habitación seleccionada no existe.',

                'cliente_id.required' => 'Debes seleccionar un cliente.',
                'cliente_id.exists' => 'El cliente seleccionado no existe.',

                'fecha_entrada.required' => 'La fecha de entrada es obligatoria.',
                'fecha_entrada.date' => 'La fecha de entrada no es válida.',

                'fecha_salida.required' => 'La fecha de salida es obligatoria.',
                'fecha_salida.date' => 'La fecha de salida no es válida.',
                'fecha_salida.after' => 'La fecha de salida debe ser posterior a la fecha de entrada.',

                'estado.required' => 'Debes seleccionar un estado.',
            ]
        );


        if ($validator->fails()) {

            return redirect()
                ->route('reservaciones.index')
                ->withErrors(
                    $validator,
                    'editar_' . $id
                )
                ->withInput()
                ->with(
                    'abrir_modal_editar',
                    $id
                );
        }


        /**
         * Comprobar que no exista otra reservación
         * para esa habitación en esas fechas.
         *
         * Ignora la reservación que estamos editando.
         */
        $ocupada = DB::table('reservaciones')
            ->where('habitacion_id', $request->habitacion_id)
            ->where('id', '!=', $id)
            ->whereRaw('LOWER(estado) != ?', ['cancelada'])
            ->where(function ($query) use ($request) {

                $query->where(
                    'fecha_entrada',
                    '<',
                    $request->fecha_salida
                )
                ->where(
                    'fecha_salida',
                    '>',
                    $request->fecha_entrada
                );

            })
            ->exists();


        if ($ocupada) {

            return redirect()
                ->route('reservaciones.index')
                ->withInput()
                ->with(
                    'abrir_modal_editar',
                    $id
                )
                ->with(
                    'error',
                    'La habitación ya está reservada dentro de esas fechas.'
                );
        }


        try {

            DB::table('reservaciones')
                ->where('id', $id)
                ->update([
                    'habitacion_id' => $request->habitacion_id,
                    'cliente_id' => $request->cliente_id,
                    'fecha_entrada' => $request->fecha_entrada,
                    'fecha_salida' => $request->fecha_salida,
                    'estado' => $request->estado,
                ]);


            return redirect()
                ->route('reservaciones.index')
                ->with(
                    'success',
                    'La reservación se actualizó correctamente.'
                );

        } catch (\Exception $e) {

            return redirect()
                ->route('reservaciones.index')
                ->with(
                    'error',
                    'No se pudo actualizar la reservación: ' .
                    $e->getMessage()
                );
        }
    }


    /**
     * Eliminar una reservación
     */
    public function destroy($id)
    {
        try {

            $reservacion = DB::table('reservaciones')
                ->where('id', $id)
                ->first();


            if (!$reservacion) {

                return redirect()
                    ->route('reservaciones.index')
                    ->with(
                        'error',
                        'La reservación no existe.'
                    );
            }


            DB::table('reservaciones')
                ->where('id', $id)
                ->delete();


            return redirect()
                ->route('reservaciones.index')
                ->with(
                    'success',
                    'La reservación se eliminó correctamente.'
                );

        } catch (\Exception $e) {

            return redirect()
                ->route('reservaciones.index')
                ->with(
                    'error',
                    'No se pudo eliminar la reservación: ' .
                    $e->getMessage()
                );
        }
    }
}