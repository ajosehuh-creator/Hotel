@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Gestión de Clientes</h4>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
            + Nuevo Cliente
        </button>
    </div>
    <div class="card-body">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Correo Electrónico</th>
                    <th>Teléfono</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($clientes as $cliente)
                <tr>
                    <td>{{ $cliente['nombre'] }}</td>
                    <td>{{ $cliente['correo'] }}</td>
                    <td>{{ $cliente['telefono'] ?? 'N/A' }}</td>
                    <td>
                        <!-- Botón Editar -->
                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal{{ $cliente['id'] }}">Editar</button>
                        <!-- Botón Eliminar -->
                        <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $cliente['id'] }}">Eliminar</button>
                    </td>
                </tr>

                <!-- Modal Editar -->
                <div class="modal fade" id="editModal{{ $cliente['id'] }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="{{ route('clientes.update', $cliente['id']) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-header">
                                    <h5 class="modal-title">Editar Cliente</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label>Nombre Completo</label>
                                        <input type="text" name="nombre" class="form-control" value="{{ $cliente['nombre'] }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Correo Electrónico</label>
                                        <input type="email" name="correo" class="form-control" value="{{ $cliente['correo'] }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Teléfono</label>
                                        <input type="text" name="telefono" class="form-control" value="{{ $cliente['telefono'] }}">
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
                <div class="modal fade" id="deleteModal{{ $cliente['id'] }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="{{ route('clientes.destroy', $cliente['id']) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <div class="modal-header">
                                    <h5 class="modal-title">Confirmar Eliminación</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    ¿Estás seguro de eliminar al cliente <strong>{{ $cliente['nombre'] }}</strong>?
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
            <form action="{{ route('clientes.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Registrar Nuevo Cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Nombre Completo</label>
                        <input type="text" name="nombre" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Correo Electrónico</label>
                        <input type="email" name="correo" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Teléfono</label>
                        <input type="text" name="telefono" class="form-control" placeholder="Opcional">
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