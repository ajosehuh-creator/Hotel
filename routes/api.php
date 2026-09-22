<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HabitacionController;

// Esto genera automáticamente las rutas GET, POST, PATCH y DELETE
Route::apiResource('habitaciones', HabitacionController::class);
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
