@extends('layouts.app')

@section('template_title')
    {{ __('Editar Movimiento') }}
@endsection

@section('content')
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-8">
                <!-- Tarjeta Principal -->
                <div class="card border-0 shadow-lg">
                    <!-- Encabezado con gradiente -->
                    <div class="card-header bg-gradient-warning text-black py-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-0">
                                    <i class="fas fa-edit me-2"></i>
                                    {{ __('Editar Movimiento') }}
                                </h4>
                                <p class="mb-0 mt-2 opacity-75">
                                    {{ $movimiento->tipoMovimiento->descripcion ?? 'Sin tipo' }}
                                </p>
                            </div>
                            <div>
                                <a class="btn btn-outline-light btn-sm" href="{{ route('movimientos.show', $movimiento->id) }}">
                                    <i class="fas fa-eye me-1"></i> {{ __('Ver Detalles') }}
                                </a>
                                <a class="btn btn-light btn-sm ms-2" href="{{ route('movimientos.index') }}">
                                    <i class="fas fa-arrow-left me-1"></i> {{ __('Volver') }}
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Información del Movimiento Actual -->
                    <div class="card-body border-bottom bg-light">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        @if($movimiento->tipoMovimiento->descripcion == 'Resguardo')
                                            <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                <i class="fas fa-shield-alt fa-lg"></i>
                                            </div>
                                        @elseif($movimiento->tipoMovimiento->descripcion == 'Préstamo')
                                            <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                <i class="fas fa-handshake fa-lg"></i>
                                            </div>
                                        @elseif($movimiento->tipoMovimiento->descripcion == 'Salida Sin Retorno')
                                            <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                <i class="fas fa-exclamation-triangle fa-lg"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-0 text-dark">{{ $movimiento->tipoMovimiento->descripcion }}</h6>
                                        <p class="mb-0 text-muted small">
                                            <i class="fas fa-box me-1"></i>
                                            {{ $movimiento->material->nombre ?? 'Material eliminado' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center">
                                    <h5 class="mb-1">{{ $movimiento->cantidad }}</h5>
                                    <small class="text-muted">{{ __('Cantidad Total') }}</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center">
                                <span class="badge
                                    @if($movimiento->estado == 'activo') bg-primary
                                    @elseif($movimiento->estado == 'devuelto') bg-success
                                    @elseif($movimiento->estado == 'perdido') bg-danger
                                    @endif fs-6 py-2">
                                    {{ ucfirst($movimiento->estado) }}
                                </span>
                                    <div class="mt-2">
                                        <small class="text-muted">{{ __('Estado Actual') }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Formulario de Edición -->
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('movimientos.update', $movimiento->id) }}" id="movimientoForm" class="needs-validation" novalidate>
                            @csrf
                            @method('PUT')

                            <!-- Información Principal -->
                            <div class="card border-0 mb-4">
                                <div class="card-header bg-white border-bottom">
                                    <h6 class="mb-0 text-primary">
                                        <i class="fas fa-info-circle me-2"></i>
                                        {{ __('Información Principal') }}
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <!-- Tipo de Movimiento -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="tipo_movimiento_id" class="form-label fw-semibold text-dark mb-2">
                                                    <span class="text-danger">*</span> {{ __('Tipo de Movimiento') }}
                                                </label>
                                                @if($movimiento->tipoMovimiento->descripcion == 'Salida Sin Retorno')
                                                    <div class="input-group">
                                                        <span class="input-group-text bg-light">
                                                            <i class="fas fa-exclamation-triangle text-danger"></i>
                                                        </span>
                                                        <input type="text" class="form-control bg-light"
                                                               value="{{ $movimiento->tipoMovimiento->descripcion }}"
                                                               readonly>
                                                        <input type="hidden" name="tipo_movimiento_id"
                                                               value="{{ $movimiento->tipo_movimiento_id }}">
                                                    </div>
                                                    <small class="form-text text-muted">
                                                        <i class="fas fa-info-circle me-1"></i>
                                                        No se puede modificar el tipo en Salidas Sin Retorno
                                                    </small>
                                                @else
                                                    <select name="tipo_movimiento_id" id="tipo_movimiento_id"
                                                            class="form-select @error('tipo_movimiento_id') is-invalid @enderror" required>
                                                        <option value="" disabled>Seleccione un tipo...</option>
                                                        @foreach($tiposMovimiento as $tipo)
                                                            @if($tipo->descripcion != 'Salida Sin Retorno' || $movimiento->tipoMovimiento->descripcion == 'Salida Sin Retorno')
                                                                <option value="{{ $tipo->id }}"
                                                                        data-descripcion="{{ $tipo->descripcion }}"
                                                                    {{ old('tipo_movimiento_id', $movimiento->tipo_movimiento_id) == $tipo->id ? 'selected' : '' }}>
                                                                    {{ $tipo->descripcion }}
                                                                </option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                    @error('tipo_movimiento_id')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                    @enderror
                                                @endif
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
                                                    <option value="" disabled>Seleccione un material...</option>
                                                    @foreach($materials as $material)
                                                        @php
                                                            $stockTotal = $material->stocks->sum('cantidad') ?? 0;
                                                        @endphp
                                                        <option value="{{ $material->id }}"
                                                                data-stock="{{ $stockTotal }}"
                                                            {{ old('material_id', $movimiento->material_id) == $material->id ? 'selected' : '' }}>
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
                                                           value="{{ old('cantidad', $movimiento->cantidad) }}"
                                                           placeholder="Ej. 1, 2.5"
                                                        {{ $movimiento->tipoMovimiento->descripcion == 'Salida Sin Retorno' ? 'readonly' : '' }}>
                                                </div>
                                                @if($movimiento->tipoMovimiento->descripcion == 'Salida Sin Retorno')
                                                    <small class="form-text text-muted">
                                                        <i class="fas fa-info-circle me-1"></i>
                                                        No se puede modificar la cantidad en Salidas Sin Retorno
                                                    </small>
                                                @endif
                                                @error('cantidad')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Fecha -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="fecha" class="form-label fw-semibold text-dark mb-2">
                                                    <span class="text-danger">*</span> {{ __('Fecha') }}
                                                </label>
                                                @if($movimiento->tipoMovimiento->descripcion == 'Salida Sin Retorno')
                                                    <div class="input-group">
                                                        <span class="input-group-text bg-light">
                                                            <i class="fas fa-calendar-alt text-muted"></i>
                                                        </span>
                                                        <input type="date" name="fecha" id="fecha"
                                                               class="form-control bg-light"
                                                               value="{{ old('fecha', $movimiento->fecha->format('Y-m-d')) }}"
                                                               readonly>
                                                    </div>
                                                    <small class="form-text text-muted">
                                                        <i class="fas fa-info-circle me-1"></i>
                                                        No se puede modificar la fecha en Salidas Sin Retorno
                                                    </small>
                                                @else
                                                    <div class="input-group">
                                                        <span class="input-group-text bg-light">
                                                            <i class="fas fa-calendar-alt text-muted"></i>
                                                        </span>
                                                        <input type="date" name="fecha" id="fecha"
                                                               class="form-control @error('fecha') is-invalid @enderror"
                                                               required
                                                               value="{{ old('fecha', $movimiento->fecha->format('Y-m-d')) }}">
                                                    </div>
                                                    @error('fecha')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                    @enderror
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Estado -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="estado" class="form-label fw-semibold text-dark mb-2">
                                                    <span class="text-danger">*</span> {{ __('Estado') }}
                                                </label>
                                                @if($movimiento->tipoMovimiento->descripcion == 'Salida Sin Retorno')
                                                    <div class="input-group">
                                                        <span class="input-group-text bg-light">
                                                            <i class="fas fa-flag text-muted"></i>
                                                        </span>
                                                        <input type="text" class="form-control bg-light"
                                                               value="{{ ucfirst($movimiento->estado) }}"
                                                               readonly>
                                                        <input type="hidden" name="estado"
                                                               value="{{ $movimiento->estado }}">
                                                    </div>
                                                    <small class="form-text text-muted">
                                                        <i class="fas fa-info-circle me-1"></i>
                                                        No se puede modificar el estado en Salidas Sin Retorno
                                                    </small>
                                                @else
                                                    <select name="estado" id="estado"
                                                            class="form-select @error('estado') is-invalid @enderror">
                                                        <option value="activo" {{ old('estado', $movimiento->estado) == 'activo' ? 'selected' : '' }}>
                                                            Activo
                                                        </option>
                                                        <option value="devuelto" {{ old('estado', $movimiento->estado) == 'devuelto' ? 'selected' : '' }}>
                                                            Devuelto
                                                        </option>
                                                        <option value="perdido" {{ old('estado', $movimiento->estado) == 'perdido' ? 'selected' : '' }}>
                                                            Perdido
                                                        </option>
                                                    </select>
                                                    @error('estado')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                    @enderror
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Información de Usuarios -->
                            <div class="card border-0 mb-4">
                                <div class="card-header bg-white border-bottom">
                                    <h6 class="mb-0 text-primary">
                                        <i class="fas fa-users me-2"></i>
                                        {{ __('Información de Usuarios') }}
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <!-- Usuario que Registró (Solo Lectura) -->
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-semibold text-dark mb-2">
                                                {{ __('Usuario que Registró') }}
                                            </label>
                                            <div class="input-group">
                                            <span class="input-group-text bg-light">
                                                <i class="fas fa-user-tie text-muted"></i>
                                            </span>
                                                <input type="text" class="form-control bg-light"
                                                       value="{{ $movimiento->usuarioRegistro->name ?? 'Usuario no disponible' }}"
                                                       readonly>
                                            </div>
                                            <small class="form-text text-muted">
                                                <i class="fas fa-info-circle me-1"></i>
                                                Este campo no puede ser modificado
                                            </small>
                                        </div>

                                        <!-- Usuario Asignado -->
                                        <div class="col-md-6 mb-3">
                                            <div class="form-group">
                                                <label for="usuario_asignado_id" class="form-label fw-semibold text-dark mb-2">
                                                    {{ __('Usuario Asignado') }}
                                                </label>
                                                <select name="usuario_asignado_id" id="usuario_asignado_id"
                                                        class="form-select @error('usuario_asignado_id') is-invalid @enderror">
                                                    <option value="">Sin usuario asignado</option>
                                                    @foreach($usuarios as $usuario)
                                                        <option value="{{ $usuario->id }}"
                                                            {{ old('usuario_asignado_id', $movimiento->usuario_asignado_id) == $usuario->id ? 'selected' : '' }}>
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
                                </div>
                            </div>

                            <!-- Información Adicional -->
                            <div class="card border-0 mb-4">
                                <div class="card-header bg-white border-bottom">
                                    <h6 class="mb-0 text-primary">
                                        <i class="fas fa-cogs me-2"></i>
                                        {{ __('Información Adicional') }}
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <!-- Fechas de Devolución -->
                                    <div class="row mb-4">
                                        <!-- Fecha Devolución Estimada -->
                                        <div class="col-md-6 mb-3">
                                            <div class="form-group">
                                                <label for="fecha_devolucion_estimada" class="form-label fw-semibold text-dark mb-2">
                                                    {{ __('Fecha Estimada de Devolución') }}
                                                </label>
                                                @if($movimiento->tipoMovimiento->descripcion == 'Salida Sin Retorno')
                                                    <div class="input-group">
                                                        <span class="input-group-text bg-light">
                                                            <i class="fas fa-calendar-times text-danger"></i>
                                                        </span>
                                                        <input type="text" class="form-control bg-light"
                                                               value="N/A (Salida Sin Retorno)"
                                                               readonly>
                                                        <input type="hidden" name="fecha_devolucion_estimada" value="">
                                                    </div>
                                                @else
                                                    <div class="input-group">
                                                        <span class="input-group-text bg-light">
                                                            <i class="fas fa-calendar-check text-muted"></i>
                                                        </span>
                                                        <input type="date" name="fecha_devolucion_estimada"
                                                               id="fecha_devolucion_estimada"
                                                               class="form-control @error('fecha_devolucion_estimada') is-invalid @enderror"
                                                               value="{{ old('fecha_devolucion_estimada', optional($movimiento->fecha_devolucion_estimada)->format('Y-m-d')) }}">
                                                    </div>
                                                    @error('fecha_devolucion_estimada')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                    @enderror
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Fecha Devolución Real -->
                                        <div class="col-md-6 mb-3">
                                            <div class="form-group">
                                                <label for="fecha_devolucion_real" class="form-label fw-semibold text-dark mb-2">
                                                    {{ __('Fecha Real de Devolución') }}
                                                </label>
                                                @if($movimiento->tipoMovimiento->descripcion == 'Salida Sin Retorno')
                                                    <div class="input-group">
                                                        <span class="input-group-text bg-light">
                                                            <i class="fas fa-calendar-times text-danger"></i>
                                                        </span>
                                                        <input type="text" class="form-control bg-light"
                                                               value="N/A (Salida Sin Retorno)"
                                                               readonly>
                                                        <input type="hidden" name="fecha_devolucion_real" value="">
                                                    </div>
                                                @else
                                                    <div class="input-group">
                                                        <span class="input-group-text bg-light">
                                                            <i class="fas fa-calendar-alt text-muted"></i>
                                                        </span>
                                                        <input type="date" name="fecha_devolucion_real"
                                                               id="fecha_devolucion_real"
                                                               class="form-control @error('fecha_devolucion_real') is-invalid @enderror"
                                                               value="{{ old('fecha_devolucion_real', optional($movimiento->fecha_devolucion_real)->format('Y-m-d')) }}">
                                                    </div>
                                                    @error('fecha_devolucion_real')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                    @enderror
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Condiciones del Resguardo (solo para Resguardos) -->
                                    @if($movimiento->tipoMovimiento->descripcion != 'Salida Sin Retorno')
                                        <div class="row mb-4">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="condiciones" class="form-label fw-semibold text-dark mb-2">
                                                        {{ __('Condiciones del Resguardo') }}
                                                        <span class="text-muted">(Solo para Resguardos)</span>
                                                    </label>
                                                    <textarea name="condiciones" id="condiciones"
                                                              class="form-control @error('condiciones') is-invalid @enderror"
                                                              rows="3"
                                                              placeholder="Especificar condiciones del resguardo...">{{ old('condiciones', $movimiento->condiciones) }}</textarea>
                                                    <small class="form-text text-muted">
                                                        <i class="fas fa-info-circle me-1"></i>
                                                        Este campo solo aplica para movimientos de tipo "Resguardo"
                                                    </small>
                                                    @error('condiciones')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Notas -->
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="notas" class="form-label fw-semibold text-dark mb-2">
                                                    {{ __('Notas') }}
                                                    <span class="text-muted">(Opcional)</span>
                                                </label>
                                                <textarea name="notas" id="notas"
                                                          class="form-control @error('notas') is-invalid @enderror"
                                                          rows="3"
                                                          placeholder="Observaciones adicionales...">{{ old('notas', $movimiento->notas) }}</textarea>
                                                @error('notas')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Alertas y Validaciones -->
                            <div class="alert alert-light border mb-4" id="stockAlert">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-info-circle me-3 text-primary"></i>
                                    <div class="small">
                                        <strong class="d-block mb-1" id="alertTitle">Información de stock</strong>
                                        <div class="row mt-2">
                                            <div class="col-md-6">
                                            <span class="d-block mb-1">
                                                <strong>Cantidad solicitada:</strong>
                                                <span id="cantidadSolicitada" class="badge bg-primary ms-1">0</span>
                                            </span>
                                            </div>
                                            <div class="col-md-6">
                                            <span class="d-block mb-1">
                                                <strong>Stock disponible:</strong>
                                                <span id="stockDisponible" class="badge bg-success ms-1">0</span>
                                            </span>
                                            </div>
                                        </div>
                                        <span class="d-block mt-2" id="alertMessage"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Botones de Acción -->
                            <div class="d-flex justify-content-between align-items-center pt-4 border-top">
                                <div>
                                    <a href="{{ route('movimientos.index') }}" class="btn btn-outline-secondary px-4">
                                        <i class="fas fa-times me-2"></i>{{ __('Cancelar') }}
                                    </a>
                                </div>
                                <div>
                                    <button type="submit" class="btn btn-warning px-4">
                                        <i class="fas fa-save me-2"></i>{{ __('Actualizar Movimiento') }}
                                    </button>
                                    @role('admin|encargado')
                                    <button type="button" class="btn btn-danger ms-2"
                                            onclick="if(confirm('¿Está seguro de eliminar este movimiento?')) { document.getElementById('deleteForm').submit(); }">
                                        <i class="fas fa-trash me-2"></i>{{ __('Eliminar') }}
                                    </button>
                                    @endrole
                                </div>
                            </div>
                        </form>

                        <!-- Formulario de Eliminación -->
                        @role('admin|encargado')
                        <form id="deleteForm" action="{{ route('movimientos.destroy', $movimiento->id) }}" method="POST" class="d-none">
                            @csrf
                            @method('DELETE')
                        </form>
                        @endrole
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Determinar si es Salida Sin Retorno
            const esSalidaSinRetorno = "{{ $movimiento->tipoMovimiento->descripcion }}" === "Salida Sin Retorno";

            if (esSalidaSinRetorno) {
                // Deshabilitar campos para Salida Sin Retorno
                $('#cantidad').attr('readonly', true);
                $('#material_id').attr('disabled', true);
                $('#stockAlert').hide();

                // Ocultar lógica de validación de stock
                $('#stockAlert').parent().hide();
            }

            // Controlar visibilidad de campos según tipo de movimiento
            function actualizarCamposCondicionales() {
                const tipoDesc = $('#tipo_movimiento_id option:selected').data('descripcion');
                const tipoId = $('#tipo_movimiento_id').val();

                if (!tipoId) {
                    $('#condicionesGroup').hide();
                    return;
                }

                // Mostrar/Ocultar campos según tipo
                if (tipoDesc === 'Resguardo') {
                    $('#condicionesGroup').show();
                } else {
                    $('#condicionesGroup').hide();
                }

                actualizarStockInfo();
            }

            // Actualizar información de stock (solo si no es Salida Sin Retorno)
            function actualizarStockInfo() {
                if (esSalidaSinRetorno) return;

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

            // Event listeners (solo si no es Salida Sin Retorno)
            if (!esSalidaSinRetorno) {
                $('#tipo_movimiento_id').change(actualizarCamposCondicionales);
                $('#material_id, #cantidad').on('input change', actualizarStockInfo);

                // Inicializar al cargar
                if ($('#tipo_movimiento_id').val()) {
                    actualizarCamposCondicionales();
                }

                // Fecha mínima para devolución estimada
                $('#fecha').change(function() {
                    const fechaSeleccionada = $(this).val();
                    $('#fecha_devolucion_estimada').attr('min', fechaSeleccionada);
                });
            }

            // Validación del formulario
            $('#movimientoForm').submit(function(e) {
                const tipoDesc = "{{ $movimiento->tipoMovimiento->descripcion }}";
                const cantidad = parseFloat($('#cantidad').val()) || 0;

                // Validar cantidad mínima (solo si no es Salida Sin Retorno)
                if (!esSalidaSinRetorno && cantidad <= 0) {
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

                // Validar stock para salidas sin retorno (solo en creación, no en edición)
                if (!esSalidaSinRetorno && tipoDesc === 'Salida Sin Retorno') {
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

    @section('styles')
        <style>
            body {
                background-color: #f8fafc;
                font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            }

            .card {
                border-radius: 12px;
                border: 1px solid #e2e8f0;
            }

            .card-header {
                border-bottom: none;
            }

            .bg-gradient-warning {
                background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            }

            .bg-gradient-primary {
                background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
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
                border-color: #f59e0b;
                box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1);
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

            .btn-warning {
                background-color: #f59e0b;
                border-color: #f59e0b;
                border-radius: 8px;
                font-weight: 500;
                padding: 0.5rem 1.5rem;
            }

            .btn-warning:hover {
                background-color: #d97706;
                border-color: #d97706;
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

            .btn-outline-light {
                border-color: rgba(255, 255, 255, 0.5);
            }

            .btn-outline-light:hover {
                background-color: rgba(255, 255, 255, 0.1);
            }

            .btn-danger {
                border-radius: 8px;
                font-weight: 500;
                padding: 0.5rem 1.5rem;
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

            .bg-info {
                background-color: #0ea5e9 !important;
            }

            .bg-warning {
                background-color: #f59e0b !important;
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

            h4, h6 {
                color: #1e293b;
            }

            .text-dark {
                color: #334155 !important;
            }

            /* Estilo para campos de solo lectura */
            .form-control.bg-light[readonly] {
                background-color: #f1f5f9 !important;
                cursor: not-allowed;
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
