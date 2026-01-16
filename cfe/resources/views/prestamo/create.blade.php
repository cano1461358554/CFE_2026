@extends('layouts.app')

@section('template_title')
    {{ __('Registrar Nuevo Préstamo') }}
@endsection

@section('content')
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-gradient-primary text-black">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0 font-weight-light">
                                <i class="fas fa-hand-holding-usd mr-2"></i> {{ __('Nuevo Préstamo') }}
                            </h4>
                            <a href="{{ route('prestamos.index') }}" class="btn btn-sm btn-light text-primary">
                                <i class="fas fa-arrow-left mr-1"></i> {{ __('Volver') }}
                            </a>
                        </div>
                    </div>

                    <div class="card-body px-5 py-4">
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                <strong>Por favor corrige los siguientes errores:</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('prestamos.store') }}" class="needs-validation" novalidate>
                            @csrf

                            <div class="form-group row mb-4">
                                <label for="fecha_prestamo" class="col-md-3 col-form-label text-md-right">
                                    {{ __('Fecha de Préstamo') }}
                                </label>
                                <div class="col-md-7">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light">
                                                <i class="fas fa-calendar-day text-primary"></i>
                                            </span>
                                        </div>
                                        <input type="date"
                                               class="form-control"
                                               id="fecha_prestamo"
                                               name="fecha_prestamo"
                                               value="{{ now()->format('Y-m-d') }}"
                                               readonly>
                                    </div>
                                    <small class="form-text text-muted">Generada automáticamente</small>
                                </div>
                            </div>

                            <div class="form-group row mb-4">
                                <label for="cantidad_prestada" class="col-md-3 col-form-label text-md-right">
                                    {{ __('Cantidad') }} <span class="text-danger">*</span>
                                </label>
                                <div class="col-md-7">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light">
                                                <i class="fas fa-balance-scale text-primary"></i>
                                            </span>
                                        </div>
                                        <input type="number"
                                               name="cantidad_prestada"
                                               id="cantidad_prestada"
                                               class="form-control @error('cantidad_prestada') is-invalid @enderror"
                                               value="{{ old('cantidad_prestada') }}"
                                               min="0.01"
                                               step="0.01"
                                               required
                                               placeholder="Ingrese la cantidad">
                                        @error('cantidad_prestada')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row mb-4">
                                <label for="material_id" class="col-md-3 col-form-label text-md-right">
                                    {{ __('Material') }} <span class="text-danger">*</span>
                                </label>
                                <div class="col-md-7">
                                    <div class="d-flex justify-content-between mb-2">
                                        <div></div>
                                        <a href="{{ route('materials.create') }}" class="btn btn-sm btn-outline-success">
                                            <i class="fas fa-plus-circle mr-1"></i> {{ __('Nuevo Material') }}
                                        </a>
                                    </div>
                                    <select name="material_id"
                                            id="material_id"
                                            class="form-control selectpicker @error('material_id') is-invalid @enderror"
                                            data-live-search="true"
                                            title="Seleccione un material"
                                            required>
                                        @foreach ($materials as $material)
                                            @php
                                                $stockTotal = $material->stocks->sum('cantidad');
                                            @endphp
                                            @if($stockTotal > 0)
                                                <option value="{{ $material->id }}"
                                                        {{ old('material_id') == $material->id ? 'selected' : '' }}
                                                        data-stock="{{ $stockTotal }}"
                                                        data-tokens="{{ $material->nombre }}">
                                                    {{ $material->nombre }} (Stock: {{ $stockTotal }})
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <small id="stockHelp" class="form-text text-muted">
                                        Solo se muestran materiales con stock disponible
                                    </small>
                                    @error('material_id')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-4">
                                <label for="user_id" class="col-md-3 col-form-label text-md-right">
                                    {{ __('Personal') }} <span class="text-danger">*</span>
                                </label>
                                <div class="col-md-7">
                                    <select name="user_id"
                                            id="user_id"
                                            class="form-control selectpicker @error('user_id') is-invalid @enderror"
                                            data-live-search="true"
                                            title="Seleccione un personal"
                                            required>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}"
                                                    {{ old('user_id') == $user->id ? 'selected' : '' }}
                                                    data-tokens="{{ $user->nombre }} {{ $user->apellido }} {{ $user->RP }}">
                                                {{ $user->nombre }} {{ $user->apellido }} (RP: {{ $user->RP }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('user_id')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-4">
                                <label for="descripcion" class="col-md-3 col-form-label text-md-right">
                                    {{ __('Descripción') }} <span class="text-danger">*</span>
                                </label>
                                <div class="col-md-7">
                                    <textarea name="descripcion"
                                              id="descripcion"
                                              class="form-control @error('descripcion') is-invalid @enderror"
                                              rows="3"
                                              required
                                              placeholder="Describa el uso del material">{{ old('descripcion') }}</textarea>
                                    @error('descripcion')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            {{-- vewrsion funcional del dia 9/01/26, este metodo hace que funcione de forma adecuada el sistema de la funcion de prestamo, si en dado caso dejase de funcionar el nuevo metodo puedes volver a este y no afectara la funcionalidad de las tablas
 --}}
                            <div class="alert alert-info mb-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fas fa-info-circle"></i>
                                        <strong>Sistema Unificado Disponible:</strong> Ahora puedes usar el sistema de Movimientos
                                        que incluye Préstamos, Resguardos y Salidas Sin Retorno.
                                    </div>
                                    <a href="{{ route('prestamos.crear-movimiento') }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-exchange-alt"></i> Usar Sistema de Movimientos
                                    </a>
                                </div>
                                <div class="mt-2">
                                    <small>
                                        El sistema de Movimientos permite:
                                        <span class="badge badge-info">Préstamos</span>
                                        <span class="badge badge-warning">Resguardos</span>
                                        <span class="badge badge-danger">Salidas Sin Retorno</span>
                                        <span class="badge badge-success">Devoluciones</span>
                                    </small>
                                </div>
                            </div>
                            <div class="form-group row mb-0">
                                <div class="col-md-7 offset-md-3">
                                    <div class="d-flex justify-content-between">
                                        <button type="reset" class="btn btn-outline-secondary">
                                            <i class="fas fa-undo mr-2"></i> {{ __('Limpiar') }}
                                        </button>
                                        <button type="submit" class="btn btn-primary px-4">
                                            <i class="fas fa-save mr-2"></i> {{ __('Registrar Préstamo') }}
                                        </button>
                                    </div>
                                </div>
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
        // Validación de formulario
        (function() {
            'use strict';
            window.addEventListener('load', function() {
                var forms = document.getElementsByClassName('needs-validation');
                var validation = Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();

        // Inicializar selectpicker si está disponible
        if (jQuery().selectpicker) {
            $('.selectpicker').selectpicker();
        }

        // Validación de stock
        document.addEventListener('DOMContentLoaded', function() {
            const materialSelect = document.getElementById('material_id');
            const cantidadInput = document.getElementById('cantidad_prestada');

            // Validar stock al seleccionar material
            materialSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const stockDisponible = parseFloat(selectedOption.getAttribute('data-stock'));

                // Actualizar el máximo permitido
                cantidadInput.setAttribute('max', stockDisponible);

                // Mostrar ayuda
                if (selectedOption.value) {
                    document.getElementById('stockHelp').textContent =
                        `Stock disponible: ${stockDisponible}. Máximo permitido: ${stockDisponible}`;
                } else {
                    document.getElementById('stockHelp').textContent =
                        'Solo se muestran materiales con stock disponible';
                }
            });

            // Validar stock al enviar el formulario
            document.querySelector('form').addEventListener('submit', function(e) {
                const selectedOption = materialSelect.options[materialSelect.selectedIndex];
                const stockDisponible = parseFloat(selectedOption.getAttribute('data-stock'));
                const cantidad = parseFloat(cantidadInput.value);

                if (cantidad > stockDisponible) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Stock insuficiente',
                        text: `No hay suficiente stock disponible. Stock actual: ${stockDisponible}`,
                        confirmButtonColor: '#3085d6',
                    });
                }
            });
        });
    </script>
@endsection
