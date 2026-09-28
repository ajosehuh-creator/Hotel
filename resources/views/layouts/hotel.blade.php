<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Módulo Hotel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { min-height: 100vh; display: flex; flex-direction: column; background-color: #f5f6fa; }
        .wrapper { display: flex; flex: 1; overflow: hidden; }
        .sidebar { width: 250px; background: #212529; color: white; padding-top: 20px;}
        .sidebar .nav-link { color: rgba(255,255,255,.75); padding: 12px 20px; border-bottom: 1px solid rgba(255,255,255,.05); }
        .sidebar .nav-link:hover { color: white; background: rgba(255,255,255,.1); }
        .main-content { flex: 1; padding: 20px; overflow-y: auto; }
        .top-navbar { background: white; box-shadow: 0 2px 4px rgba(0,0,0,.08); padding: 15px 20px; display: flex; justify-content: space-between; align-items: center;}
    </style>
</head>
<body>
    <!-- Barra superior para volver a Ciudad Digital -->
    <div class="top-navbar">
        <h4 class="mb-0 text-primary"><i class="bi bi-buildings"></i> Módulo Hotel</h4>
        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Volver a Ciudad Digital
        </a>
    </div>

    <div class="wrapper">
        <!-- Barra Lateral (Sidebar) -->
        <nav class="sidebar">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('hotel.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i> Inicio Hotel</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('reservaciones.index') }}"><i class="bi bi-calendar-check me-2"></i> Reservaciones</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('habitaciones.index') }}"><i class="bi bi-door-open me-2"></i> Habitaciones</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('tipos-habitacion.index') }}"><i class="bi bi-tags me-2"></i> Tipos de Hab.</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('clientes.index') }}"><i class="bi bi-people me-2"></i> Clientes</a>
                </li>
            </ul>
        </nav>

        <!-- Contenido Dinámico -->
        <main class="main-content">
            <div class="container-fluid">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @yield('content')
            </div>
        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
