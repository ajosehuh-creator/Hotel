<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Reservaciones</title>


    {{-- BOOTSTRAP --}}
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >


    {{-- BOOTSTRAP ICONS --}}
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >


    {{-- SWEET ALERT 2 POR CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <style>

        body {
            background-color: #f5f6fa;
            min-height: 100vh;
        }

        .contenedor-principal {
            margin-top: 45px;
            margin-bottom: 45px;
        }

        .card-principal {
            border: 0;
            border-radius: 16px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, .08);
            overflow: hidden;
        }

        .encabezado {
            background-color: white;
            padding: 25px;
            border-bottom: 1px solid #eeeeee;
        }

        .encabezado h2 {
            font-weight: 700;
            margin-bottom: 5px;
        }

        .table {
            margin-bottom: 0;
        }

        .table thead th {
            background: #212529;
            color: white;
            border: none;
            padding: 15px;
            white-space: nowrap;
        }

        .table tbody td {
            vertical-align: middle;
            padding: 14px;
        }

        .btn {
            border-radius: 8px;
        }

        .form-control,
        .form-select {
            border-radius: 8px;
            padding: 10px;
        }

        .modal-content {
            border: none;
            border-radius: 15px;
            overflow: hidden;
        }

        .modal-header {
            border-bottom: none;
        }

        .modal-footer {
            border-top: 1px solid #eeeeee;
        }

        .badge {
            padding: 8px 12px;
            font-size: 13px;
        }

        .sin-registros {
            padding: 70px 20px;
            text-align: center;
        }

        .sin-registros i {
            font-size: 60px;
            color: #adb5bd;
        }

        .cliente-correo {
            font-size: 12px;
            color: #6c757d;
        }

    </style>

</head>


<body>


<div class="container contenedor-principal">


    <div class="card card-principal">


        {{-- ============================================= --}}
        {{-- ENCABEZADO --}}
        {{-- ============================================= --}}

        <div class="encabezado">

            <div class="d-flex
                        justify-content-between
                        align-items-center
                        flex-wrap
                        gap-3">

                <div>

                    <h2>

                        <i class="bi bi-calendar-check me-2"></i>

                        Reservaciones

                    </h2>

                    <span class="text-muted">

                        Administración de reservaciones del hotel

                    </span>

                </div>


                <button
                    type="button"
                    class="btn btn-primary"
                    data-bs-toggle="modal"
                    data-bs-target="#modalCrear">

                    <i class="bi bi-plus-circle me-1"></i>

                    Nueva reservación

                </button>

            </div>

        </div>



        {{-- ============================================= --}}
        {{-- TABLA --}}
        {{-- ============================================= --}}

        <div class="card-body p-0">


            @if($reservaciones->count() > 0)


                <div class="table-responsive">


                    <table class="table table-hover">


                        <thead>

                        <tr>

                            <th>ID</th>

                            <th>Cliente</th>

                            <th>Habitación</th>

                            <th>Tipo</th>

                            <th>Precio/Noche</th>

                            <th>Entrada</th>

                            <th>Salida</th>

                            <th>Estado</th>

                            <th class="text-center">
                                Acciones
                            </th>

                        </tr>

                        </thead>


                        <tbody>


                        @foreach($reservaciones as $reservacion)


                            <tr>


                                <td>

                                    #{{ $reservacion->id }}

                                </td>



                                {{-- CLIENTE --}}

                                <td>

                                    <div class="fw-semibold">

                                        <i class="bi bi-person-circle
                                                  text-primary
                                                  me-1"></i>

                                        {{ $reservacion->cliente_nombre }}

                                    </div>


                                    @if($reservacion->cliente_correo)

                                        <div class="cliente-correo">

                                            {{ $reservacion->cliente_correo }}

                                        </div>

                                    @endif

                                </td>



                                {{-- HABITACIÓN --}}

                                <td>

                                    <i class="bi bi-door-open me-1"></i>

                                    Habitación
                                    {{ $reservacion->habitacion_numero }}

                                </td>



                                {{-- TIPO --}}

                                <td>

                                    {{ $reservacion->tipo_habitacion ?? 'Sin tipo' }}

                                </td>



                                {{-- PRECIO --}}

                                <td>

                                    <strong>

                                        ${{ number_format(
                                            $reservacion->precio_noche,
                                            2
                                        ) }}

                                    </strong>

                                </td>



                                {{-- ENTRADA --}}

                                <td>

                                    {{ \Carbon\Carbon::parse(
                                        $reservacion->fecha_entrada
                                    )->format('d/m/Y') }}

                                </td>



                                {{-- SALIDA --}}

                                <td>

                                    {{ \Carbon\Carbon::parse(
                                        $reservacion->fecha_salida
                                    )->format('d/m/Y') }}

                                </td>



                                {{-- ESTADO --}}

                                <td>


                                    @php

                                        $estadoActual =
                                            strtolower(
                                                $reservacion->estado
                                            );

                                    @endphp


                                    @if($estadoActual === 'pendiente')

                                        <span class="badge
                                                     bg-warning
                                                     text-dark">

                                            Pendiente

                                        </span>


                                    @elseif($estadoActual === 'confirmada')

                                        <span class="badge bg-success">

                                            Confirmada

                                        </span>


                                    @elseif($estadoActual === 'cancelada')

                                        <span class="badge bg-danger">

                                            Cancelada

                                        </span>


                                    @elseif($estadoActual === 'finalizada')

                                        <span class="badge bg-secondary">

                                            Finalizada

                                        </span>


                                    @else

                                        <span class="badge
                                                     bg-info
                                                     text-dark">

                                            {{ $reservacion->estado }}

                                        </span>

                                    @endif


                                </td>



                                {{-- ACCIONES --}}

                                <td>


                                    <div class="d-flex
                                                justify-content-center
                                                gap-2">


                                        {{-- EDITAR --}}

                                        <button
                                            type="button"
                                            class="btn
                                                   btn-warning
                                                   btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalEditar{{ $reservacion->id }}"
                                            title="Editar">

                                            <i class="bi bi-pencil-square"></i>

                                        </button>



                                        {{-- ELIMINAR --}}

                                        <form
                                            id="formEliminar{{ $reservacion->id }}"
                                            action="{{ route(
                                                'reservaciones.destroy',
                                                $reservacion->id
                                            ) }}"
                                            method="POST">


                                            @csrf

                                            @method('DELETE')


                                            <button
                                                type="button"
                                                class="btn
                                                       btn-danger
                                                       btn-sm"
                                                onclick="confirmarEliminar(
                                                    {{ $reservacion->id }}
                                                )"
                                                title="Eliminar">

                                                <i class="bi bi-trash"></i>

                                            </button>


                                        </form>


                                    </div>


                                </td>


                            </tr>


                        @endforeach


                        </tbody>


                    </table>


                </div>


            @else


                <div class="sin-registros">


                    <i class="bi bi-calendar-x"></i>


                    <h4 class="mt-3">

                        No existen reservaciones

                    </h4>


                    <p class="text-muted">

                        Todavía no tienes reservaciones registradas.

                    </p>


                    <button
                        type="button"
                        class="btn btn-primary"
                        data-bs-toggle="modal"
                        data-bs-target="#modalCrear">

                        <i class="bi bi-plus-circle me-1"></i>

                        Crear reservación

                    </button>


                </div>


            @endif


        </div>


    </div>


</div>



{{-- ====================================================== --}}
{{-- MODAL CREAR --}}
{{-- ====================================================== --}}

<div
    class="modal fade"
    id="modalCrear"
    tabindex="-1">


    <div class="modal-dialog
                modal-lg
                modal-dialog-centered">


        <div class="modal-content">


            <form
                action="{{ route('reservaciones.store') }}"
                method="POST">


                @csrf


                <div class="modal-header
                            bg-primary
                            text-white">


                    <h5 class="modal-title">

                        <i class="bi bi-calendar-plus me-2"></i>

                        Nueva reservación

                    </h5>


                    <button
                        type="button"
                        class="btn-close
                               btn-close-white"
                        data-bs-dismiss="modal">
                    </button>


                </div>



                <div class="modal-body">


                    {{-- ERRORES --}}

                    @if($errors->getBag('crear')->any())


                        <div class="alert alert-danger">


                            <strong>

                                Corrige los siguientes datos:

                            </strong>


                            <ul class="mb-0 mt-2">


                                @foreach(
                                    $errors->getBag('crear')->all()
                                    as $error
                                )


                                    <li>

                                        {{ $error }}

                                    </li>


                                @endforeach


                            </ul>


                        </div>


                    @endif



                    <div class="row g-3">



                        {{-- CLIENTE --}}

                        <div class="col-md-6">


                            <label class="form-label">

                                Cliente *

                            </label>


                            <select
                                name="cliente_id"
                                class="form-select"
                                required>


                                <option value="">

                                    Selecciona un cliente

                                </option>


                                @foreach($clientes as $cliente)


                                    <option
                                        value="{{ $cliente->id }}"
                                        {{ old('cliente_id') == $cliente->id
                                            ? 'selected'
                                            : ''
                                        }}>

                                        {{ $cliente->nombre }}

                                        @if($cliente->correo)

                                            - {{ $cliente->correo }}

                                        @endif

                                    </option>


                                @endforeach


                            </select>


                        </div>



                        {{-- HABITACIÓN --}}

                        <div class="col-md-6">


                            <label class="form-label">

                                Habitación *

                            </label>


                            <select
                                name="habitacion_id"
                                class="form-select"
                                required>


                                <option value="">

                                    Selecciona una habitación

                                </option>


                                @foreach($habitaciones as $habitacion)


                                    <option
                                        value="{{ $habitacion->id }}"
                                        {{ old('habitacion_id') == $habitacion->id
                                            ? 'selected'
                                            : ''
                                        }}>

                                        Habitación
                                        {{ $habitacion->numero }}

                                        @if($habitacion->tipo_habitacion)

                                            -
                                            {{ $habitacion->tipo_habitacion }}

                                        @endif

                                        -
                                        ${{ number_format(
                                            $habitacion->precio_noche,
                                            2
                                        ) }}

                                    </option>


                                @endforeach


                            </select>


                        </div>

                        {{-- FECHA ENTRADA --}}

                        <div class="col-md-6">


                            <label class="form-label">

                                Fecha de entrada *

                            </label>


                            <input
                                type="date"
                                name="fecha_entrada"
                                id="fechaEntradaCrear"
                                class="form-control"
                                value="{{ old('fecha_entrada') }}"
                                required>


                        </div>



                        {{-- FECHA SALIDA --}}

                        <div class="col-md-6">


                            <label class="form-label">

                                Fecha de salida *

                            </label>


                            <input
                                type="date"
                                name="fecha_salida"
                                id="fechaSalidaCrear"
                                class="form-control"
                                value="{{ old('fecha_salida') }}"
                                required>


                        </div>



                        {{-- ESTADO --}}

                        <div class="col-12">


                            <label class="form-label">

                                Estado *

                            </label>


                            <select
                                name="estado"
                                class="form-select"
                                required>


                                <option value="">

                                    Selecciona el estado

                                </option>


                                <option
                                    value="Pendiente"
                                    {{ old('estado') === 'Pendiente'
                                        ? 'selected'
                                        : ''
                                    }}>

                                    Pendiente

                                </option>


                                <option
                                    value="Confirmada"
                                    {{ old('estado') === 'Confirmada'
                                        ? 'selected'
                                        : ''
                                    }}>

                                    Confirmada

                                </option>


                                <option
                                    value="Cancelada"
                                    {{ old('estado') === 'Cancelada'
                                        ? 'selected'
                                        : ''
                                    }}>

                                    Cancelada

                                </option>


                                <option
                                    value="Finalizada"
                                    {{ old('estado') === 'Finalizada'
                                        ? 'selected'
                                        : ''
                                    }}>

                                    Finalizada

                                </option>


                            </select>


                        </div>


                    </div>


                </div>



                <div class="modal-footer">


                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">

                        <i class="bi bi-x-circle me-1"></i>

                        Cancelar

                    </button>


                    <button
                        type="submit"
                        class="btn btn-primary">

                        <i class="bi bi-save me-1"></i>

                        Guardar reservación

                    </button>


                </div>


            </form>


        </div>


    </div>


</div>



{{-- ====================================================== --}}
{{-- MODALES EDITAR --}}
{{-- ====================================================== --}}

@foreach($reservaciones as $reservacion)


    @php

        $modalTieneError =
            session('abrir_modal_editar') == $reservacion->id;

        $erroresEditar =
            $errors->getBag(
                'editar_' . $reservacion->id
            );

    @endphp


    <div
        class="modal fade"
        id="modalEditar{{ $reservacion->id }}"
        tabindex="-1">


        <div class="modal-dialog
                    modal-lg
                    modal-dialog-centered">


            <div class="modal-content">


                <form
                    action="{{ route(
                        'reservaciones.update',
                        $reservacion->id
                    ) }}"
                    method="POST">


                    @csrf

                    @method('PUT')



                    <div class="modal-header bg-warning">


                        <h5 class="modal-title">

                            <i class="bi bi-pencil-square me-2"></i>

                            Editar reservación
                            #{{ $reservacion->id }}

                        </h5>


                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal">
                        </button>


                    </div>



                    <div class="modal-body">



                        @if($erroresEditar->any())


                            <div class="alert alert-danger">


                                <strong>

                                    Corrige los siguientes datos:

                                </strong>


                                <ul class="mb-0 mt-2">


                                    @foreach($erroresEditar->all() as $error)


                                        <li>

                                            {{ $error }}

                                        </li>


                                    @endforeach


                                </ul>


                            </div>


                        @endif



                        <div class="row g-3">



                            {{-- CLIENTE --}}

                            <div class="col-md-6">


                                <label class="form-label">

                                    Cliente *

                                </label>


                                <select
                                    name="cliente_id"
                                    class="form-select"
                                    required>


                                    @foreach($clientes as $cliente)


                                        @php

                                            $clienteActual =
                                                $modalTieneError
                                                ? old('cliente_id')
                                                : $reservacion->cliente_id;

                                        @endphp


                                        <option
                                            value="{{ $cliente->id }}"
                                            {{ $clienteActual == $cliente->id
                                                ? 'selected'
                                                : ''
                                            }}>

                                            {{ $cliente->nombre }}

                                        </option>


                                    @endforeach


                                </select>


                            </div>



                            {{-- HABITACIÓN --}}

                            <div class="col-md-6">


                                <label class="form-label">

                                    Habitación *

                                </label>


                                <select
                                    name="habitacion_id"
                                    class="form-select"
                                    required>


                                    @foreach($habitaciones as $habitacion)


                                        @php

                                            $habitacionActual =
                                                $modalTieneError
                                                ? old('habitacion_id')
                                                : $reservacion->habitacion_id;

                                        @endphp


                                        <option
                                            value="{{ $habitacion->id }}"
                                            {{ $habitacionActual == $habitacion->id
                                                ? 'selected'
                                                : ''
                                            }}>

                                            Habitación
                                            {{ $habitacion->numero }}

                                            @if($habitacion->tipo_habitacion)

                                                -
                                                {{ $habitacion->tipo_habitacion }}

                                            @endif

                                            -
                                            ${{ number_format(
                                                $habitacion->precio_noche,
                                                2
                                            ) }}

                                        </option>


                                    @endforeach


                                </select>


                            </div>



                            {{-- FECHA ENTRADA --}}

                            <div class="col-md-6">


                                <label class="form-label">

                                    Fecha de entrada *

                                </label>


                                <input
                                    type="date"
                                    name="fecha_entrada"
                                    class="form-control"
                                    value="{{ $modalTieneError
                                        ? old('fecha_entrada')
                                        : $reservacion->fecha_entrada
                                    }}"
                                    required>


                            </div>



                            {{-- FECHA SALIDA --}}

                            <div class="col-md-6">


                                <label class="form-label">

                                    Fecha de salida *

                                </label>


                                <input
                                    type="date"
                                    name="fecha_salida"
                                    class="form-control"
                                    value="{{ $modalTieneError
                                        ? old('fecha_salida')
                                        : $reservacion->fecha_salida
                                    }}"
                                    required>


                            </div>



                            {{-- ESTADO --}}

                            <div class="col-12">


                                <label class="form-label">

                                    Estado *

                                </label>


                                @php

                                    $estadoActual =
                                        $modalTieneError
                                        ? old('estado')
                                        : $reservacion->estado;

                                @endphp


                                <select
                                    name="estado"
                                    class="form-select"
                                    required>


                                    <option
                                        value="Pendiente"
                                        {{ $estadoActual === 'Pendiente'
                                            ? 'selected'
                                            : ''
                                        }}>

                                        Pendiente

                                    </option>


                                    <option
                                        value="Confirmada"
                                        {{ $estadoActual === 'Confirmada'
                                            ? 'selected'
                                            : ''
                                        }}>

                                        Confirmada

                                    </option>


                                    <option
                                        value="Cancelada"
                                        {{ $estadoActual === 'Cancelada'
                                            ? 'selected'
                                            : ''
                                        }}>

                                        Cancelada

                                    </option>


                                    <option
                                        value="Finalizada"
                                        {{ $estadoActual === 'Finalizada'
                                            ? 'selected'
                                            : ''
                                        }}>

                                        Finalizada

                                    </option>


                                </select>


                            </div>


                        </div>


                    </div>



                    <div class="modal-footer">


                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">

                            <i class="bi bi-x-circle me-1"></i>

                            Cancelar

                        </button>


                        <button
                            type="submit"
                            class="btn btn-warning">

                            <i class="bi bi-save me-1"></i>

                            Guardar cambios

                        </button>


                    </div>


                </form>


            </div>


        </div>


    </div>


@endforeach



{{-- ====================================================== --}}
{{-- BOOTSTRAP --}}
{{-- ====================================================== --}}

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>



{{-- ====================================================== --}}
{{-- JAVASCRIPT --}}
{{-- ====================================================== --}}

<script>


    /**
     * Confirmar eliminación con SweetAlert
     */
    function confirmarEliminar(id)
    {

        Swal.fire({

            title: '¿Eliminar reservación?',

            text: 'Esta acción no se puede deshacer.',

            icon: 'warning',

            showCancelButton: true,

            confirmButtonColor: '#dc3545',

            cancelButtonColor: '#6c757d',

            confirmButtonText: 'Sí, eliminar',

            cancelButtonText: 'Cancelar'

        }).then((result) => {

            if (result.isConfirmed) {

                document
                    .getElementById(
                        'formEliminar' + id
                    )
                    .submit();

            }

        });

    }



    /**
     * Fecha mínima para salida
     * al crear una reservación.
     */
    const fechaEntrada =
        document.getElementById(
            'fechaEntradaCrear'
        );

    const fechaSalida =
        document.getElementById(
            'fechaSalidaCrear'
        );


    if (fechaEntrada && fechaSalida) {

        fechaEntrada.addEventListener(
            'change',
            function () {

                fechaSalida.min =
                    this.value;

                if (
                    fechaSalida.value &&
                    fechaSalida.value <= this.value
                ) {

                    fechaSalida.value = '';

                }

            }
        );

    }


</script>



{{-- ====================================================== --}}
{{-- SWEET ALERT DE ÉXITO --}}
{{-- ====================================================== --}}

@if(session('success'))


    <script>

        Swal.fire({

            icon: 'success',

            title: '¡Correcto!',

            text: @json(session('success')),

            confirmButtonText: 'Aceptar'

        });

    </script>


@endif



{{-- ====================================================== --}}
{{-- SWEET ALERT ERROR --}}
{{-- ====================================================== --}}

@if(session('error'))


    <script>

        Swal.fire({

            icon: 'error',

            title: 'Error',

            text: @json(session('error')),

            confirmButtonText: 'Aceptar'

        });

    </script>


@endif



{{-- ====================================================== --}}
{{-- ABRIR MODAL CREAR SI HUBO ERROR --}}
{{-- ====================================================== --}}

@if(session('abrir_modal_crear'))


    <script>

        document.addEventListener(
            'DOMContentLoaded',
            function () {

                const modal =
                    new bootstrap.Modal(
                        document.getElementById(
                            'modalCrear'
                        )
                    );

                modal.show();

            }
        );

    </script>


@endif



{{-- ====================================================== --}}
{{-- ABRIR MODAL EDITAR SI HUBO ERROR --}}
{{-- ====================================================== --}}

@if(session('abrir_modal_editar'))


    <script>

        document.addEventListener(
            'DOMContentLoaded',
            function () {

                const modal =
                    new bootstrap.Modal(
                        document.getElementById(
                            'modalEditar{{ session(
                                'abrir_modal_editar'
                            ) }}'
                        )
                    );

                modal.show();

            }
        );

    </script>


@endif


</body>

</html>