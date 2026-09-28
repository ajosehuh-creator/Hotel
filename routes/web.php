<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TipoHabitacionController;
use App\Http\Controllers\HabitacionController;
use App\Http\Controllers\ReservacionController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    
    // 1. Dashboard Principal de Jetstream (Ciudad Digital)
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // 2. Ruta específica para el Dashboard del Hotel
    Route::get('/hotel/dashboard', [DashboardController::class, 'index'])->name('hotel.dashboard');
    
    // 3. El resto de tus rutas (Habitaciones, Clientes, etc.) se quedan exactamente igual:
    Route::resource('clientes', ClienteController::class);
    Route::resource('tipos-habitacion', TipoHabitacionController::class);
    Route::resource('habitaciones', HabitacionController::class);
    Route::resource('reservaciones', ReservacionController::class)->only(['index', 'store', 'update', 'destroy']);
});