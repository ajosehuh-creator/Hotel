@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Gestión de Habitaciones</h4>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
            + Nueva Habitación
        </button>
    </div>
    <div class="card-body">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Número</th>
                    <th>Tipo</th>
                    <th>Precio / Noche</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($habitaciones as $hab)
                <tr>
                    <td>{{ $hab['numero'] }}</td>
                    <!-- Mostramos el nombre del tipo gracias al JOIN de Supabase -->
                    <td>{{ $hab['tipos_habitacion']['nombre'] ?? 'Sin tipo asignado' }}</td>
                    <td>${{ number_format($hab['precio_noche'], 2) }}</td>
                    <td>
                        <span class="badge bg-{{ $hab['estado'] == 'Disponible' ? 'success' : ($hab['estado'] == 'Ocupada' ? 'danger' : 'warning') }}">
                            {{ $hab['estado'] ?? 'No definido' }}
                        </span>
                    </td>
                    <td>
                        <!-- Botón Editar -->
                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal{{ $hab['id'] }}">Editar</button>
                        <!-- Botón Eliminar -->
                        <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $hab['id'] }}">Eliminar</button>
                    </td>
                </tr>

                <!-- Modal Editar -->
                <div class="modal fade" id="editModal{{ $hab['id'] }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="{{ route('habitaciones.update', $hab['id']) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-header">
                                    <h5 class="modal-title">Editar Habitación {{ $hab['numero'] }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label>Número de Habitación</label>
                                        <input type="text" name="numero" class="form-control" value="{{ $hab['numero'] }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Tipo de Habitación</label>
                                        <select name="tipo_id" class="form-select" required>
                                            <option value="">Seleccione un tipo...</option>
                                            @foreach($tipos as $tipo)
                                                <!-- Evaluamos cuál era el tipo asignado para marcarlo como 'selected' -->
                                                <option value="{{ $tipo['id'] }}" {{ $hab['tipo_id'] == $tipo['id'] ? 'selected' : '' }}>
                                                    {{ $tipo['nombre'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label>Precio por Noche</label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="number" step="0.01" name="precio_noche" class="form-control" value="{{ $hab['precio_noche'] }}" required>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label>Estado</label>
                                        <select name="estado" class="form-select">
                                            <option value="Disponible" {{ $hab['estado'] == 'Disponible' ? 'selected' : '' }}>Disponible</option>
                                            <option value="Ocupada" {{ $hab['estado'] == 'Ocupada' ? 'selected' : '' }}>Ocupada</option>
                                            <option value="Mantenimiento" {{ $hab['estado'] == 'Mantenimiento' ? 'selected' : '' }}>Mantenimiento</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Actualizar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Modal Eliminar -->
                <div class="modal fade" id="deleteModal{{ $hab['id'] }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="{{ route('habitaciones.destroy', $hab['id']) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <div class="modal-header">
                                    <h5 class="modal-title">Confirmar Eliminación</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    ¿Estás seguro de eliminar la habitación <strong>{{ $hab['numero'] }}</strong>?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-danger">Eliminar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Crear -->
<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('habitaciones.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Registrar Nueva Habitación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Número de Habitación</label>
                        <input type="text" name="numero" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Tipo de Habitación</label>
                        <select name="tipo_id" class="form-select" required>
                            <option value="">Seleccione un tipo...</option>
                            <!-- Llenamos el select iterando sobre los tipos traídos del controlador -->
                            @foreach($tipos as $tipo)
                                <option value="{{ $tipo['id'] }}">{{ $tipo['nombre'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Precio por Noche</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" step="0.01" name="precio_noche" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>Estado inicial</label>
                        <select name="estado" class="form-select">
                            <option value="Disponible" selected>Disponible</option>
                            <option value="Ocupada">Ocupada</option>
                            <option value="Mantenimiento">Mantenimiento</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection