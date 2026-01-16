@extends('layouts.app')

@section('template_title')
    {{ __('Editar Registro de Stock') }}
@endsection

@section('content')
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-gradient-info text-black">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0 font-weight-light">
                                <i class="fas fa-edit mr-2"></i> {{ __('Editar Registro de Stock') }}
                            </h4>
                            <a href="{{ route('stocks.index') }}" class="btn btn-sm btn-light text-info">
                                <i class="fas fa-arrow-left mr-1"></i> {{ __('Volver al Listado') }}
                            </a>
                        </div>
                    </div>

                    <div class="card-body px-5 py-4">

                        <form method="POST" action="{{ route('stocks.update', $stock->id) }}" enctype="multipart/form-data" class="needs-validation" novalidate>
                            @csrf
                            @method('PUT')

                            <div class="form-group row mb-4">
                                <label for="cantidad" class="col-md-3 col-form-label text-md-right">
                                    {{ __('Cantidad') }} <span class="text-danger">*</span>
                                </label>
                                <div class="col-md-7">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light">
                                                <i class="fas fa-hashtag text-info"></i>
                                            </span>
                                        </div>
                                        <input type="number"
                                               name="cantidad"
                                               id="cantidad"
                                               class="form-control rounded-right @error('cantidad') is-invalid @enderror"
                                               value="{{ old('cantidad', $stock->cantidad) }}"
                                               required
                                               min="1"
                                               placeholder="Ingrese la cantidad">
                                        @error('cantidad')
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
                                    <select name="material_id"
                                            id="material_id"
                                            class="form-control selectpicker @error('material_id') is-invalid @enderror"
                                            data-live-search="true"
                                            title="Seleccione un material"
                                            required>
                                        @foreach ($materials as $material)
                                            <option value="{{ $material->id }}"
                                                    {{ old('material_id', $stock->material_id) == $material->id ? 'selected' : '' }}
                                                    data-tokens="{{ $material->nombre }}">
                                                {{ $material->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('material_id')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-4">
                                <label for="almacen_id" class="col-md-3 col-form-label text-md-right">
                                    {{ __('Almacén') }} <span class="text-danger">*</span>
                                </label>
                                <div class="col-md-7">
                                    <select name="almacen_id"
                                            id="almacen_id"
                                            class="form-control selectpicker @error('almacen_id') is-invalid @enderror"
                                            data-live-search="true"
                                            title="Seleccione un almacén"
                                            required>
                                        @foreach ($almacens as $almacen)
                                            <option value="{{ $almacen->id }}"
                                                    {{ old('almacen_id', $stock->almacen_id) == $almacen->id ? 'selected' : '' }}
                                                    data-tokens="{{ $almacen->nombre }}">
                                                {{ $almacen->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('almacen_id')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-7 offset-md-3">
                                    <div class="d-flex justify-content-end">
                                        <button type="reset" class="btn btn-outline-secondary mr-3">
                                            <i class="fas fa-undo mr-2"></i> {{ __('Restablecer') }}
                                        </button>
                                        <button type="submit" class="btn btn-info btn-lg px-4">
                                            <i class="fas fa-save mr-2"></i> {{ __('Actualizar Registro') }}
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
    </script>
@endsection
