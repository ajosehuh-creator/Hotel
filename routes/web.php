<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TipoHabitacionController;
use App\Http\Controllers\HabitacionController;
<<<<<<< HEAD
use App\Http\Controllers\ReservacionController;

=======
use App\Http\Controllers\ClienteController;

Route::resource('clientes', ClienteController::class);
>>>>>>> 15dc63e (Agrego y actualizo las vistas)

// Usamos resource en web para habilitar las vistas
Route::resource('tipos-habitacion', TipoHabitacionController::class);
Route::resource('habitaciones', HabitacionController::class);

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

//reservaciones
Route::resource('reservaciones', ReservacionController::class)
    ->only(['index', 'store', 'update', 'destroy']);