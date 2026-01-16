@extends('layouts.app')

@section('template_title')
    {{ __('Crear Nuevo Movimiento') }}
@endsection

@section('content')
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <!-- Tarjeta Principal -->
                <div class="card border-0 shadow">
                    <!-- Encabezado -->
                    <div class="card-header bg-white border-bottom-0 pt-4">
                        <div class="text-center mb-4">
                            <h4 class="fw-bold text-dark mb-3">
                                <i class="fas fa-exchange-alt me-2 text-primary"></i>
                                {{ __('Registrar Movimiento') }}
                            </h4>
                            <p class="text-muted mb-0">Complete la información requerida para registrar un nuevo movimiento</p>
                        </div>
                    </div>

                    <!-- Contenido del Formulario -->
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('movimientos.store') }}" id="movimientoForm" class="needs-validation" novalidate>
                            @csrf

                            <!-- Fecha Automática -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="form-label fw-semibold text-dark mb-2">
                                            <i class="fas fa-calendar-day me-2 text-primary"></i>
                                            {{ __('Fecha del Movimiento') }}
                                        </label>
                                        <div class="input-group">
                                        <span class="input-group-text bg-light">
                                            <i class="fas fa-calendar-alt text-muted"></i>
                                        </span>
                                            <input type="date" name="fecha" id="fecha"
                                                   class="form-control border-start-0 bg-light"
                                                   value="{{ date('Y-m-d') }}" readonly>
                                        </div>
                                        <small class="form-text text-muted mt-2">
                                            La fecha se genera automáticamente
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <!-- Información Principal -->
                            <div class="row g-3 mb-4">
                                <!-- Tipo de Movimiento -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tipo_movimiento_id" class="form-label fw-semibold text-dark mb-2">
                                            <span class="text-danger">*</span> {{ __('Tipo de Movimiento') }}
                                        </label>
                                        <select name="tipo_movimiento_id" id="tipo_movimiento_id"
                                                class="form-select @error('tipo_movimiento_id') is-invalid @enderror" required>
                                            <option value="" disabled selected>Seleccione un tipo...</option>
                                            @foreach($tiposMovimiento as $tipo)
                                                <option value="{{ $tipo->id }}"
                                                        data-descripcion="{{ $tipo->descripcion }}"
                                                    {{ old('tipo_movimiento_id') == $tipo->id ? 'selected' : '' }}>
                                                    {{ $tipo->descripcion }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('tipo_movimiento_id')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Material -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="material_id" class="form-label fw-semibold text-dark mb-2">
                                            <span class="text-danger">*</span> {{ __('Material') }}
                                        </label>
                                        <select name="material_id" id="material_id"
                                                class="form-select @error('material_id') is-invalid @enderror" required>
                                            <option value="" disabled selected>Seleccione un material...</option>
                                            @foreach($materials as $material)
                                                @php
                                                    $stockTotal = $material->stocks->sum('cantidad') ?? 0;
                                                @endphp
                                                <option value="{{ $material->id }}"
                                                        data-stock="{{ $stockTotal }}"
                                                    {{ old('material_id') == $material->id ? 'selected' : '' }}>
                                                    {{ $material->nombre }}
                                                    @if($stockTotal > 0)
                                                        <span class="badge bg-success float-end">
                                                        {{ $stockTotal }}
                                                    </span>
                                                    @else
                                                        <span class="badge bg-danger float-end">
                                                        0
                                                    </span>
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('material_id')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Cantidad -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="cantidad" class="form-label fw-semibold text-dark mb-2">
                                            <span class="text-danger">*</span> {{ __('Cantidad') }}
                                        </label>
                                        <div class="input-group">
                                        <span class="input-group-text bg-light">
                                            <i class="fas fa-hashtag text-muted"></i>
                                        </span>
                                            <input type="number" name="cantidad" id="cantidad"
                                                   class="form-control @error('cantidad') is-invalid @enderror"
                                                   required min="0.01" step="0.01"
                                                   value="{{ old('cantidad') }}"
                                                   placeholder="Ej. 1, 2.5">
                                        </div>
                                        @error('cantidad')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <!-- Mostrar usuario que registra (automático) -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold text-dark mb-2">
                                        {{ __('Usuario que Registra') }}
                                    </label>
                                    <div class="input-group">
        <span class="input-group-text bg-light">
            <i class="fas fa-user-check text-muted"></i>
        </span>
                                        <input type="text" class="form-control"
                                               value="{{ auth()->user()->name }}" readonly>
                                    </div>
                                    <small class="form-text text-muted">
                                        Usted está registrando este movimiento
                                    </small>
                                </div>



                                <!-- Usuario Asignado -->
                                <div class="col-md-6" id="usuarioAsignadoGroup">
                                    <div class="form-group">
                                        <label for="usuario_asignado_id" class="form-label fw-semibold text-dark mb-2">
                                            <span class="text-danger">*</span> {{ __('Usuario Asignado') }}
                                        </label>
                                        <select name="usuario_asignado_id" id="usuario_asignado_id"
                                                class="form-select @error('usuario_asignado_id') is-invalid @enderror">
                                            <option value="" selected>Seleccione usuario...</option>
                                            @foreach($usuarios as $usuario)
                                                <option value="{{ $usuario->id }}"
                                                    {{ old('usuario_asignado_id') == $usuario->id ? 'selected' : '' }}>
                                                    @if($usuario->nombre && $usuario->apellido)
                                                        {{ $usuario->nombre }} {{ $usuario->apellido }}
                                                    @elseif($usuario->name)
                                                        {{ $usuario->name }}
                                                    @endif
                                                    @if($usuario->RP)
                                                        <small class="text-muted">({{ $usuario->RP }})</small>
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('usuario_asignado_id')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Fecha de Devolución Estimada -->
                            <div class="row mb-4" id="fechaDevolucionGroup">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="fecha_devolucion_estimada" class="form-label fw-semibold text-dark mb-2">
                                            {{ __('Fecha Estimada de Devolución') }}
                                        </label>
                                        <div class="input-group">
                                        <span class="input-group-text bg-light">
                                            <i class="fas fa-calendar-check text-muted"></i>
                                        </span>
                                            <input type="date" name="fecha_devolucion_estimada"
                                                   id="fecha_devolucion_estimada"
                                                   class="form-control @error('fecha_devolucion_estimada') is-invalid @enderror"
                                                   value="{{ old('fecha_devolucion_estimada') }}">
                                        </div>
                                        <small class="form-text text-muted mt-2">
                                            Seleccione la fecha estimada para la devolución
                                        </small>
                                        @error('fecha_devolucion_estimada')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Condiciones de Resguardo (Condicional) -->
                            <div class="row mb-4" id="condicionesGroup" style="display: none;">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="condiciones" class="form-label fw-semibold text-dark mb-2">
                                            {{ __('Condiciones del Resguardo') }}
                                        </label>
                                        <textarea name="condiciones" id="condiciones"
                                                  class="form-control @error('condiciones') is-invalid @enderror"
                                                  rows="3"
                                                  placeholder="Especificar condiciones del resguardo...">{{ old('condiciones') }}</textarea>
                                        @error('condiciones')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Notas (Opcional) -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="notas" class="form-label fw-semibold text-dark mb-2">
                                            {{ __('Notas') }}
                                            <span class="text-muted">(Opcional)</span>
                                        </label>
                                        <textarea name="notas" id="notas"
                                                  class="form-control @error('notas') is-invalid @enderror"
                                                  rows="2"
                                                  placeholder="Observaciones adicionales...">{{ old('notas') }}</textarea>
                                        @error('notas')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Alerta de Stock -->
                            <div class="alert alert-light border mb-4" id="stockAlert" style="display: none;">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-info-circle me-3 text-primary"></i>
                                    <div class="small">
                                        <strong class="d-block mb-1" id="alertTitle"></strong>
                                        <div class="row mt-2">
                                            <div class="col-md-6">
                                            <span class="d-block mb-1">
                                                <strong>Cantidad solicitada:</strong>
                                                <span id="cantidadSolicitada" class="badge bg-primary ms-1"></span>
                                            </span>
                                            </div>
                                            <div class="col-md-6">
                                            <span class="d-block mb-1">
                                                <strong>Stock disponible:</strong>
                                                <span id="stockDisponible" class="badge bg-success ms-1"></span>
                                            </span>
                                            </div>
                                        </div>
                                        <span class="d-block mt-2" id="alertMessage"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Botones de Acción -->
                            <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                                <a href="{{ route('movimientos.index') }}" class="btn btn-outline-secondary px-4">
                                    <i class="fas fa-times me-2"></i>{{ __('Cancelar') }}
                                </a>

                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="fas fa-check me-2"></i>{{ __('Registrar Movimiento') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Configurar fecha mínima para devolución estimada (mañana)
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            const tomorrowStr = tomorrow.toISOString().split('T')[0];
            $('#fecha_devolucion_estimada').attr('min', tomorrowStr);

            // Controlar visibilidad de campos según tipo de movimiento
            function actualizarCamposCondicionales() {
                const tipoDesc = $('#tipo_movimiento_id option:selected').data('descripcion');
                const tipoId = $('#tipo_movimiento_id').val();

                if (!tipoId) {
                    $('#fechaDevolucionGroup').hide();
                    $('#usuarioAsignadoGroup').hide();
                    $('#condicionesGroup').hide();
                    return;
                }

                // Mostrar/Ocultar campos según tipo
                if (tipoDesc === 'Salida Sin Retorno') {
                    $('#usuarioAsignadoGroup').hide();
                    $('#fechaDevolucionGroup').hide();
                    $('#condicionesGroup').hide();
                    $('#usuario_asignado_id').prop('required', false);
                    $('#fecha_devolucion_estimada').prop('required', false);
                } else if (tipoDesc === 'Devolución') {
                    $('#fechaDevolucionGroup').hide();
                    $('#condicionesGroup').hide();
                    $('#fecha_devolucion_estimada').prop('required', false);
                    $('#usuario_asignado_id').prop('required', true);
                } else if (tipoDesc === 'Resguardo') {
                    $('#usuario_asignado_id').prop('required', true);
                    $('#fecha_devolucion_estimada').prop('required', false);
                    $('#fechaDevolucionGroup').show();
                    $('#condicionesGroup').show(); // Mostrar condiciones solo para Resguardo
                } else if (tipoDesc === 'Préstamo') {
                    $('#usuario_asignado_id').prop('required', true);
                    $('#fecha_devolucion_estimada').prop('required', true);
                    $('#fechaDevolucionGroup').show();
                    $('#condicionesGroup').hide();
                } else {
                    $('#usuario_asignado_id').prop('required', false);
                    $('#fechaDevolucionGroup').show();
                    $('#condicionesGroup').hide();
                }

                actualizarStockInfo();
            }

            // Actualizar información de stock
            function actualizarStockInfo() {
                const materialId = $('#material_id').val();
                const cantidad = parseFloat($('#cantidad').val()) || 0;
                const tipoDesc = $('#tipo_movimiento_id option:selected').data('descripcion');

                if (materialId && cantidad > 0) {
                    const stockTotal = parseFloat($('#material_id option:selected').data('stock')) || 0;

                    $('#stockAlert').show();
                    $('#cantidadSolicitada').text(cantidad);
                    $('#stockDisponible').text(stockTotal);

                    if (tipoDesc === 'Salida Sin Retorno') {
                        if (cantidad > stockTotal) {
                            $('#alertTitle').text('Stock insuficiente');
                            $('#stockAlert').removeClass('alert-light alert-success').addClass('alert-danger');
                            $('#alertMessage').html(`<span class="text-danger">Faltan ${(cantidad - stockTotal).toFixed(2)} unidades</span>`);
                        } else {
                            $('#alertTitle').text('Stock disponible');
                            $('#stockAlert').removeClass('alert-danger alert-light').addClass('alert-success');
                            $('#alertMessage').text(`Nuevo stock: ${(stockTotal - cantidad).toFixed(2)}`);
                        }
                    } else {
                        $('#alertTitle').text('Información de stock');
                        $('#stockAlert').removeClass('alert-danger alert-success').addClass('alert-light');
                        $('#alertMessage').text('Esta operación no afecta el stock disponible');
                    }
                } else {
                    $('#stockAlert').hide();
                }
            }

            // Event listeners
            $('#tipo_movimiento_id').change(actualizarCamposCondicionales);
            $('#material_id, #cantidad').on('input change', actualizarStockInfo);

            // Inicializar al cargar
            if ($('#tipo_movimiento_id').val()) {
                actualizarCamposCondicionales();
            }

            // Validación del formulario
            $('#movimientoForm').submit(function(e) {
                const tipoDesc = $('#tipo_movimiento_id option:selected').data('descripcion');
                const usuarioAsignado = $('#usuario_asignado_id').val();
                const cantidad = parseFloat($('#cantidad').val()) || 0;

                // Validar usuario asignado para préstamos y resguardos
                if ((tipoDesc === 'Préstamo' || tipoDesc === 'Resguardo') && !usuarioAsignado) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Usuario requerido',
                        text: 'Debe seleccionar un usuario asignado para este tipo de movimiento.',
                        confirmButtonText: 'Entendido'
                    });
                    $('#usuario_asignado_id').focus();
                    return false;
                }

                // Validar cantidad mínima
                if (cantidad <= 0) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Cantidad inválida',
                        text: 'La cantidad debe ser mayor a 0.',
                        confirmButtonText: 'Entendido'
                    });
                    $('#cantidad').focus();
                    return false;
                }

                // Validar stock para salidas sin retorno
                if (tipoDesc === 'Salida Sin Retorno') {
                    const stockTotal = parseFloat($('#material_id option:selected').data('stock')) || 0;

                    if (cantidad > stockTotal) {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'error',
                            title: 'Stock insuficiente',
                            html: `<div class="text-start">
                            <p class="mb-2"><strong>Material:</strong> ${$('#material_id option:selected').text().split('(')[0].trim()}</p>
                            <p class="mb-2"><strong>Cantidad solicitada:</strong> ${cantidad}</p>
                            <p class="mb-2"><strong>Stock disponible:</strong> ${stockTotal}</p>
                            <p class="text-danger mb-0"><strong>Faltan:</strong> ${(cantidad - stockTotal).toFixed(2)} unidades</p>
                        </div>`,
                            confirmButtonText: 'Entendido'
                        });
                        return false;
                    }
                }

                return true;
            });
        });
    </script>

    @if($errors->any())
        <script>
            $(document).ready(function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Errores en el formulario',
                    html: `<div class="text-start">
                    @foreach($errors->all() as $error)
                    <p class="mb-2">• {{ $error }}</p>
                    @endforeach
                    </div>`,
                    confirmButtonText: 'Entendido'
                });
            });
        </script>
    @endif

    <style>
        /* Estilo minimalista similar a la captura */
        body {
            background-color: #f8fafc;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }

        .card {
            border-radius: 12px;
            border: 1px solid #e2e8f0;
        }

        .card-header {
            background: transparent;
            border-bottom: 1px solid #e2e8f0;
        }

        .form-label {
            color: #334155;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .form-control, .form-select {
            border-radius: 8px;
            border: 1px solid #cbd5e1;
            background-color: white;
            transition: all 0.2s;
            font-size: 0.95rem;
        }

        .form-control:focus, .form-select:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            background-color: white;
        }

        .form-control[readonly] {
            background-color: #f1f5f9;
            border-color: #cbd5e1;
            cursor: not-allowed;
        }

        .input-group-text {
            background-color: #f8fafc;
            border: 1px solid #cbd5e1;
            border-right: none;
            color: #64748b;
        }

        .input-group .form-control {
            border-left: none;
        }

        .btn-primary {
            background-color: #3b82f6;
            border-color: #3b82f6;
            border-radius: 8px;
            font-weight: 500;
            padding: 0.5rem 1.5rem;
        }

        .btn-primary:hover {
            background-color: #2563eb;
            border-color: #2563eb;
        }

        .btn-outline-secondary {
            border-color: #cbd5e1;
            color: #64748b;
            border-radius: 8px;
            font-weight: 500;
            padding: 0.5rem 1.5rem;
        }

        .btn-outline-secondary:hover {
            background-color: #f1f5f9;
            border-color: #94a3b8;
            color: #475569;
        }

        .alert {
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            background-color: #f8fafc;
        }

        .alert-danger {
            background-color: #fef2f2;
            border-color: #fecaca;
            color: #dc2626;
        }

        .alert-success {
            background-color: #f0fdf4;
            border-color: #bbf7d0;
            color: #16a34a;
        }

        .badge {
            border-radius: 6px;
            font-weight: 500;
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }

        .bg-success {
            background-color: #10b981 !important;
        }

        .bg-danger {
            background-color: #ef4444 !important;
        }

        .bg-primary {
            background-color: #3b82f6 !important;
        }

        .text-primary {
            color: #3b82f6 !important;
        }

        .text-muted {
            color: #64748b !important;
        }

        .border-top {
            border-color: #e2e8f0 !important;
        }

        h4 {
            color: #1e293b;
            font-weight: 600;
        }

        /* Ajustes responsive */
        @media (max-width: 768px) {
            .card-body {
                padding: 1.5rem !important;
            }

            .row.g-3 > div {
                margin-bottom: 1rem;
            }
        }
    </style>
@endsection
