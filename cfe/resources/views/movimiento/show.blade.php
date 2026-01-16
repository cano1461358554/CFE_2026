@extends('layouts.app')

@section('template_title')
    {{ __('Detalles del Movimiento') }}
@endsection

@section('content')
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-8">
                <!-- Tarjeta Principal -->
                <div class="card border-0 shadow-lg">
                    <!-- Encabezado con gradiente -->
                    <div class="card-header bg-gradient-primary text-black py-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-0">
                                    <i class="fas fa-exchange-alt me-2"></i>
                                    {{ __('Detalles del Movimiento') }}
                                </h4>
{{--                                <p class="mb-0 mt-2 opacity-75">--}}
{{--                                    ID: #{{ $movimiento->id }} • {{ $movimiento->tipoMovimiento->descripcion ?? 'Sin tipo' }}--}}
{{--                                </p>--}}
                            </div>
                            <div>
                                <a class="btn btn-outline-light btn-sm" href="{{ route('movimientos.index') }}">
                                    <i class="fas fa-arrow-left me-1"></i> {{ __('Volver') }}
                                </a>
                                @role('admin|encargado')
                                <a class="btn btn-light btn-sm ms-2" href="{{ route('movimientos.edit', $movimiento->id) }}">
                                    <i class="fas fa-edit me-1"></i> {{ __('Editar') }}
                                </a>
                                @endrole
                            </div>
                        </div>
                    </div>

                    <!-- Cuerpo del Card -->
                    <div class="card-body p-4">
                        <!-- Información Principal -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card border-0 bg-light mb-3">
                                    <div class="card-body">
                                        <h6 class="card-subtitle mb-2 text-muted">
                                            <i class="fas fa-info-circle me-2 text-primary"></i>
                                            {{ __('Información Básica') }}
                                        </h6>
                                        <div class="row mt-3">
                                            <div class="col-md-6 mb-2">
                                                <strong class="text-dark">{{ __('Tipo de Movimiento') }}</strong><br>
                                                @if($movimiento->tipoMovimiento->descripcion == 'Resguardo')
                                                    <span class="badge bg-info fs-6 py-1">
                                                    <i class="fas fa-shield-alt"></i> Resguardo
                                                </span>
                                                @elseif($movimiento->tipoMovimiento->descripcion == 'Préstamo')
                                                    <span class="badge bg-warning fs-6 py-1">
                                                    <i class="fas fa-handshake"></i> Préstamo
                                                </span>
                                                @elseif($movimiento->tipoMovimiento->descripcion == 'Salida Sin Retorno')
                                                    <span class="badge bg-danger fs-6 py-1">
                                                    <i class="fas fa-exclamation-triangle"></i> Salida Sin Retorno
                                                </span>
                                                @else
                                                    <span class="badge bg-secondary fs-6 py-1">
                                                    {{ $movimiento->tipoMovimiento->descripcion }}
                                                </span>
                                                @endif
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <strong class="text-dark">{{ __('Estado') }}</strong><br>
                                                @if($movimiento->estado == 'activo')
                                                    @if($movimiento->completamente_devuelto)
                                                        <span class="badge bg-success fs-6 py-1">Completamente Devuelto</span>
                                                    @elseif($movimiento->cantidad_pendiente > 0 && ($movimiento->esPrestamo() || $movimiento->esResguardo()))
                                                        <span class="badge bg-warning fs-6 py-1">Pendiente ({{ $movimiento->cantidad_pendiente }})</span>
                                                    @else
                                                        <span class="badge bg-primary fs-6 py-1">Activo</span>
                                                    @endif
                                                @elseif($movimiento->estado == 'devuelto')
                                                    <span class="badge bg-secondary fs-6 py-1">Devuelto</span>
                                                @elseif($movimiento->estado == 'perdido')
                                                    <span class="badge bg-danger fs-6 py-1">Perdido</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card border-0 bg-light mb-3">
                                    <div class="card-body">
                                        <h6 class="card-subtitle mb-2 text-muted">
                                            <i class="fas fa-calculator me-2 text-primary"></i>
                                            {{ __('Cantidades') }}
                                        </h6>
                                        <div class="row mt-3">
                                            <div class="col-md-6 mb-2">
                                                <strong class="text-dark">{{ __('Cantidad Total') }}</strong><br>
                                                <span class="h5 text-dark">{{ $movimiento->cantidad }}</span>
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <strong class="text-dark">{{ __('Cantidad Devuelta') }}</strong><br>
                                                <span class="h5 text-success">{{ $movimiento->cantidad_devuelta }}</span>
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <strong class="text-dark">{{ __('Pendiente') }}</strong><br>
                                                <span class="h5 text-warning">{{ $movimiento->cantidad_pendiente }}</span>
                                            </div>
                                            @if($movimiento->cantidad_pendiente > 0)
                                                <div class="col-md-6 mb-2">
                                                    <strong class="text-dark">{{ __('Porcentaje Devuelto') }}</strong><br>
                                                    <div class="progress mt-1" style="height: 10px;">
                                                        <div class="progress-bar bg-success"
                                                             role="progressbar"
                                                             style="width: {{ $movimiento->porcentaje_devuelto }}%"
                                                             aria-valuenow="{{ $movimiento->porcentaje_devuelto }}"
                                                             aria-valuemin="0"
                                                             aria-valuemax="100">
                                                        </div>
                                                    </div>
                                                    <small class="text-muted">{{ round($movimiento->porcentaje_devuelto, 1) }}%</small>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Información Detallada -->
                        <div class="row">
                            <!-- Columna Izquierda: Material y Fechas -->
                            <div class="col-md-6">
                                <div class="card border-0 mb-4">
                                    <div class="card-header bg-white border-bottom">
                                        <h6 class="mb-0 text-primary">
                                            <i class="fas fa-box me-2"></i>
                                            {{ __('Información del Material') }}
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <strong class="text-dark">{{ __('Material') }}</strong><br>
                                            <div class="mt-1">
                                                <i class="fas fa-box-open text-muted me-2"></i>
                                                <span class="h5">{{ $movimiento->material->nombre ?? 'Material eliminado' }}</span>
                                                @if($movimiento->material && $movimiento->material->trashed())
                                                    <span class="badge bg-warning ms-2">Eliminado</span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <strong class="text-dark">{{ __('Fecha de Movimiento') }}</strong><br>
                                                <div class="mt-1">
                                                    <i class="fas fa-calendar-day text-muted me-2"></i>
                                                    {{ $movimiento->fecha->format('d/m/Y') }}
                                                </div>
                                            </div>
                                            @if($movimiento->fecha_devolucion_estimada)
                                                <div class="col-md-6 mb-3">
                                                    <strong class="text-dark">{{ __('Devolución Estimada') }}</strong><br>
                                                    <div class="mt-1">
                                                        <i class="fas fa-calendar-check text-muted me-2"></i>
                                                        {{ $movimiento->fecha_devolucion_estimada->format('d/m/Y') }}
                                                        @if($movimiento->fecha_devolucion_estimada < now() && $movimiento->estado == 'activo')
                                                            <span class="badge bg-danger ms-2">Vencido</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif
                                            @if($movimiento->fecha_devolucion_real)
                                                <div class="col-md-6 mb-3">
                                                    <strong class="text-dark">{{ __('Devolución Real') }}</strong><br>
                                                    <div class="mt-1">
                                                        <i class="fas fa-calendar-alt text-muted me-2"></i>
                                                        {{ $movimiento->fecha_devolucion_real->format('d/m/Y') }}
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Columna Derecha: Usuarios -->
                            <div class="col-md-6">
                                <div class="card border-0 mb-4">
                                    <div class="card-header bg-white border-bottom">
                                        <h6 class="mb-0 text-primary">
                                            <i class="fas fa-users me-2"></i>
                                            {{ __('Información de Usuarios') }}
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <!-- Usuario que Registra -->
                                        <!-- Reemplaza esta sección: -->
                                        <div class="mb-4">
                                            <strong class="text-dark">{{ __('Usuario que Registró') }}</strong>
                                            <div class="mt-2 p-3 bg-light rounded">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                            <i class="fas fa-user-tie"></i>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <h6 class="mb-1">
                                                            @if($movimiento->usuarioRegistro)
                                                                {{ $movimiento->usuarioRegistro->name ?? 'Usuario no disponible' }}
                                                            @else
                                                                {{ auth()->user()->name ?? 'Usuario no disponible' }}
                                                            @endif
                                                        </h6>
                                                        <p class="mb-0 text-muted small">
                                                            <i class="fas fa-envelope me-1"></i>
                                                            @if($movimiento->usuarioRegistro)
                                                                {{ $movimiento->usuarioRegistro->email ?? 'Sin email' }}
                                                            @else
                                                                {{ auth()->user()->email ?? 'Sin email' }}
                                                            @endif
                                                        </p>
                                                        <p class="mb-0 text-muted small">
                                                            <i class="fas fa-clock me-1"></i>
                                                            Registrado el: {{ $movimiento->created_at->format('d/m/Y H:i') }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Usuario Asignado -->
                                        @if($movimiento->usuario_asignado_id)
                                            <div class="mb-4">
                                                <strong class="text-dark">{{ __('Usuario Asignado') }}</strong>
                                                <div class="mt-2 p-3 bg-light rounded">
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0">
                                                            <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                                <i class="fas fa-user"></i>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1 ms-3">
                                                            <h6 class="mb-1">
                                                                {{ $movimiento->usuarioAsignado->nombre ?? $movimiento->usuarioAsignado->name ?? 'Usuario no disponible' }}
                                                                @if($movimiento->usuarioAsignado->apellido)
                                                                    {{ ' ' . $movimiento->usuarioAsignado->apellido }}
                                                                @endif
                                                            </h6>
                                                            <p class="mb-0 text-muted small">
                                                                <i class="fas fa-envelope me-1"></i>
                                                                {{ $movimiento->usuarioAsignado->email ?? 'Sin email' }}
                                                            </p>
                                                            @if($movimiento->usuarioAsignado->RP)
                                                                <p class="mb-0 text-muted small">
                                                                    <i class="fas fa-id-card me-1"></i>
                                                                    RP: {{ $movimiento->usuarioAsignado->RP }}
                                                                </p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Información Adicional -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card border-0">
                                    <div class="card-header bg-white border-bottom">
                                        <h6 class="mb-0 text-primary">
                                            <i class="fas fa-sticky-note me-2"></i>
                                            {{ __('Información Adicional') }}
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        @if($movimiento->notas)
                                            <div class="mb-3">
                                                <strong class="text-dark">{{ __('Notas') }}</strong><br>
                                                <div class="mt-1 p-3 bg-light rounded">
                                                    <i class="fas fa-quote-left text-muted me-2"></i>
                                                    {{ $movimiento->notas }}
                                                </div>
                                            </div>
                                        @endif

                                        @if($movimiento->condiciones && $movimiento->esResguardo())
                                            <div class="mb-3">
                                                <strong class="text-dark">{{ __('Condiciones del Resguardo') }}</strong><br>
                                                <div class="mt-1 p-3 bg-light rounded">
                                                    <i class="fas fa-file-contract text-muted me-2"></i>
                                                    {{ $movimiento->condiciones }}
                                                </div>
                                            </div>
                                        @endif

                                        @if($movimiento->es_sin_retorno)
                                            <div class="alert alert-warning">
                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                <strong>Salida Sin Retorno:</strong> Este material ha sido descontado permanentemente del inventario.
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Historial de Devoluciones -->
                        @if($movimiento->devolucions && $movimiento->devolucions->count() > 0)
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <div class="card border-0">
                                        <div class="card-header bg-white border-bottom">
                                            <h6 class="mb-0 text-primary">
                                                <i class="fas fa-history me-2"></i>
                                                {{ __('Historial de Devoluciones') }}
                                                <span class="badge bg-primary ms-2">{{ $movimiento->devolucions->count() }}</span>
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-hover">
                                                    <thead class="thead-light">
                                                    <tr>
                                                        <th>{{ __('Fecha') }}</th>
                                                        <th>{{ __('Cantidad Devuelta') }}</th>
                                                        <th>{{ __('Almacén') }}</th>
                                                        <th>{{ __('Estado del Material') }}</th>
                                                        <th>{{ __('Registrado por') }}</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($movimiento->devolucions as $devolucion)
                                                        <tr>
                                                            <td>{{ $devolucion->fecha_devolucion->format('d/m/Y') }}</td>
                                                            <td>
                                                                <span class="badge bg-success">{{ $devolucion->cantidad_devuelta }}</span>
                                                            </td>
                                                            <td>{{ $devolucion->almacen->nombre ?? 'N/A' }}</td>
                                                            <td>
                                                        <span class="d-inline-block text-truncate" style="max-width: 150px;"
                                                              title="{{ $devolucion->descripcion_estado }}">
                                                            {{ $devolucion->descripcion_estado }}
                                                        </span>
                                                            </td>
                                                            <td>
                                                                <small class="text-muted">
                                                                    {{ $devolucion->created_at->format('d/m/Y H:i') }}
                                                                </small>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Botones de Acción -->
                        <div class="d-flex justify-content-between align-items-center mt-4 pt-4 border-top">
                            <div>
                                <small class="text-muted">
                                    <i class="fas fa-clock me-1"></i>
                                    {{ __('Creado') }}: {{ $movimiento->created_at->format('d/m/Y H:i') }}
                                    @if($movimiento->updated_at != $movimiento->created_at)
                                        • {{ __('Actualizado') }}: {{ $movimiento->updated_at->format('d/m/Y H:i') }}
                                    @endif
                                </small>
                            </div>
                            <div>
                                @role('admin|encargado')
                                <!-- Botón de Devolución (si aplica) -->
                                @if(($movimiento->esPrestamo() || $movimiento->esResguardo()) &&
                                    $movimiento->estado == 'activo' &&
                                    $movimiento->cantidad_pendiente > 0)
{{--                                    <button class="btn btn-info btn-devolucion"--}}
{{--                                            data-toggle="modal"--}}
{{--                                            data-target="#devolucionModal"--}}
{{--                                            data-movimiento-id="{{ $movimiento->id }}"--}}
{{--                                            data-material="{{ $movimiento->material->nombre ?? 'Sin material' }}"--}}
{{--                                            data-cantidad="{{ $movimiento->cantidad_pendiente }}"--}}
{{--                                            data-user="{{ $movimiento->usuarioAsignado->name ?? 'Sin usuario' }}"--}}
{{--                                            data-descripcion="{{ $movimiento->notas }}">--}}
{{--                                        <i class="fas fa-undo me-1"></i> {{ __('Registrar Devolución') }}--}}
{{--                                    </button>--}}
                                @endif
                                @endrole
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        body {
            background-color: #f8fafc;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }

        .card {
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            transition: all 0.3s;
        }

        .card:hover {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .bg-gradient-primary {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        }

        .text-primary {
            color: #3b82f6 !important;
        }

        .bg-light {
            background-color: #f8fafc !important;
        }

        .badge {
            border-radius: 8px;
            font-weight: 500;
            padding: 0.35em 0.65em;
        }

        .border-bottom {
            border-bottom: 2px solid #e2e8f0 !important;
        }

        .progress {
            border-radius: 8px;
            background-color: #e2e8f0;
        }

        .progress-bar {
            border-radius: 8px;
        }

        .table th {
            background-color: #f1f5f9;
            color: #334155;
            font-weight: 600;
            border-top: none;
        }

        .table td {
            vertical-align: middle;
        }

        .btn-outline-light {
            border-color: rgba(255, 255, 255, 0.5);
        }

        .btn-outline-light:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .alert-warning {
            background-color: #fef3c7;
            border-color: #fbbf24;
            color: #92400e;
            border-radius: 8px;
        }

        h4, h6 {
            color: #1e293b;
        }

        .text-dark {
            color: #334155 !important;
        }

        .text-muted {
            color: #64748b !important;
        }

        .rounded-circle {
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
@endsection
