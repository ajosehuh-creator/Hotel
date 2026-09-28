@extends('layouts.hotel')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-speedometer2 me-2"></i>Panel de Control</h2>
        <p class="text-muted mb-0">Resumen general del Hotel</p>
    </div>

    <div class="row g-4">
        {{-- Tarjeta: Reservaciones Activas --}}
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-primary text-white">
                <div class="card-body d-flex flex-column justify-content-center align-items-center text-center p-4">
                    <i class="bi bi-calendar-check display-4 mb-2"></i>
                    <h3 class="fw-bold display-6 mb-0">{{ $reservacionesActivas }}</h3>
                    <span class="fs-5">Reservaciones Activas</span>
                </div>
            </div>
        </div>

        {{-- Tarjeta: Total Habitaciones --}}
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-info text-white">
                <div class="card-body d-flex flex-column justify-content-center align-items-center text-center p-4">
                    <i class="bi bi-door-open display-4 mb-2"></i>
                    <h3 class="fw-bold display-6 mb-0">{{ $totalHabitaciones }}</h3>
                    <span class="fs-5">Total Habitaciones</span>
                </div>
            </div>
        </div>

        {{-- Tarjeta: Total Clientes --}}
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-success text-white">
                <div class="card-body d-flex flex-column justify-content-center align-items-center text-center p-4">
                    <i class="bi bi-people display-4 mb-2"></i>
                    <h3 class="fw-bold display-6 mb-0">{{ $totalClientes }}</h3>
                    <span class="fs-5">Clientes Registrados</span>
                </div>
            </div>
        </div>

        {{-- Tarjeta: Histórico Reservaciones --}}
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-secondary text-white">
                <div class="card-body d-flex flex-column justify-content-center align-items-center text-center p-4">
                    <i class="bi bi-clock-history display-4 mb-2"></i>
                    <h3 class="fw-bold display-6 mb-0">{{ $totalReservaciones }}</h3>
                    <span class="fs-5">Histórico Total</span>
                </div>
            </div>
        </div>
    </div>

    
</div>
@endsection