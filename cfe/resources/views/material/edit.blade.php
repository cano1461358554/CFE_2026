@extends('layouts.app')

@section('template_title')
    {{ __('Editar Material') }}
@endsection

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-lg border-0">
                    <!-- Encabezado con gradiente -->
                    <div class="card-header bg-gradient-primary text-black py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="mb-0">
                                <i class="fas fa-edit mr-2"></i> Editar Material
                            </h3>
                            <div class="badge bg-white text-primary px-3 py-2 shadow-sm">
                                <i class="fas fa-fingerprint mr-1"></i> ID: {{ $material->clave }}
                            </div>
                        </div>
                    </div>

                    <!-- Cuerpo del formulario -->
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('materials.update', $material->id) }}" role="form">
                            @method('PATCH')
                            @csrf

                            <div class="row">
                                <!-- Columna Izquierda -->
                                <div class="col-md-6 pr-lg-4">
                                    <div class="form-group mb-4">
                                        <label class="font-weight-bold text-dark">
                                            <i class="fas fa-tag text-primary mr-1"></i> Nombre del Material <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="nombre"
                                               class="form-control border-2 @error('nombre') is-invalid @enderror"
                                               value="{{ old('nombre', $material->nombre) }}" required>
                                        @error('nombre')
                                        <div class="invalid-feedback d-block">
                                            <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-4">
                                        <label class="font-weight-bold text-dark">
                                            <i class="fas fa-key text-primary mr-1"></i> Clave Identificadora
                                        </label>
                                        <div class="input-group">
                                            <input type="text" class="form-control border-2 bg-light"
                                                   value="{{ $material->clave }}" readonly>
                                            <div class="input-group-append">
                                            <span class="input-group-text bg-light border-2">
                                                <i class="fas fa-lock text-muted"></i>
                                            </span>
                                            </div>
                                        </div>
                                        <small class="form-text text-muted ml-1">
                                            Identificador único del sistema (no editable)
                                        </small>
                                    </div>

                                    <div class="form-group mb-4">
                                        <label class="font-weight-bold text-dark">
                                            <i class="fas fa-certificate text-primary mr-1"></i> Marca <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="marca"
                                               class="form-control border-2 @error('marca') is-invalid @enderror"
                                               value="{{ old('marca', $material->marca) }}" required>
                                        @error('marca')
                                        <div class="invalid-feedback d-block">
                                            <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label class="font-weight-bold text-dark">
                                            <i class="fas fa-align-left text-primary mr-1"></i> Descripción
                                        </label>
                                        <textarea name="descripcion" rows="3"
                                                  class="form-control border-2 @error('descripcion') is-invalid @enderror">{{ old('descripcion', $material->descripcion) }}</textarea>
                                        @error('descripcion')
                                        <div class="invalid-feedback d-block">
                                            <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Columna Derecha -->
                                <div class="col-md-6 pl-lg-4">
                                    <div class="form-group mb-4">
                                        <label class="font-weight-bold text-dark">
                                            <i class="fas fa-tags text-primary mr-1"></i> Categoría <span class="text-danger">*</span>
                                        </label>
                                        <select name="categoria_id"
                                                class="form-control border-2 @error('categoria_id') is-invalid @enderror" required>
                                            <option value="">Seleccione una categoría...</option>
                                            @foreach ($categorias as $categoria)
                                                <option value="{{ $categoria->id }}" {{ old('categoria_id', $material->categoria_id) == $categoria->id ? 'selected' : '' }}>
                                                    {{ $categoria->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('categoria_id')
                                        <div class="invalid-feedback d-block">
                                            <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-4">
                                        <label class="font-weight-bold text-dark">
                                            <i class="fas fa-cubes text-primary mr-1"></i> Tipo de Material <span class="text-danger">*</span>
                                        </label>
                                        <select name="tipomaterial_id"
                                                class="form-control border-2 @error('tipomaterial_id') is-invalid @enderror" required>
                                            <option value="">Seleccione un tipo...</option>
                                            @foreach ($tiposMaterial as $tipoMaterial)
                                                <option value="{{ $tipoMaterial->id }}" {{ old('tipomaterial_id', $material->tipomaterial_id) == $tipoMaterial->id ? 'selected' : '' }}>
                                                    {{ $tipoMaterial->descripcion }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('tipomaterial_id')
                                        <div class="invalid-feedback d-block">
                                            <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-4">
                                        <label class="font-weight-bold text-dark">
                                            <i class="fas fa-ruler-combined text-primary mr-1"></i> Unidad de Medida <span class="text-danger">*</span>
                                        </label>
                                        <select name="unidadmedida_id"
                                                class="form-control border-2 @error('unidadmedida_id') is-invalid @enderror" required>
                                            <option value="">Seleccione una unidad...</option>
                                            @foreach ($unidadesMedida as $unidadMedida)
                                                <option value="{{ $unidadMedida->id }}" {{ old('unidadmedida_id', $material->unidadmedida_id) == $unidadMedida->id ? 'selected' : '' }}>
                                                    {{ $unidadMedida->descripcion_unidad }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('unidadmedida_id')
                                        <div class="invalid-feedback d-block">
                                            <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-4">
                                        <label class="font-weight-bold text-dark">
                                            <i class="fas fa-warehouse text-primary mr-1"></i> Almacén <span class="text-danger">*</span>
                                        </label>
                                        <select name="almacen_id"
                                                class="form-control border-2 @error('almacen_id') is-invalid @enderror" required>
                                            <option value="">Seleccione un almacén...</option>
                                            @foreach ($almacens as $almacen)
                                                <option value="{{ $almacen->id }}" {{ old('almacen_id', $material->almacen_id) == $almacen->id ? 'selected' : '' }}>
                                                    {{ $almacen->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('almacen_id')
                                        <div class="invalid-feedback d-block">
                                            <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label class="font-weight-bold text-dark">
                                            <i class="fas fa-map-marker-alt text-primary mr-1"></i> Estante
                                        </label>
                                        <div class="input-group">
                                            <input type="text" name="estante"
                                                   class="form-control border-2 bg-light"
                                                   value="{{ old('estante', $material->estante) }}" readonly>
                                            <div class="input-group-append">
                                            <span class="input-group-text bg-light border-2">
                                                <i class="fas fa-lock text-muted"></i>
                                            </span>
                                            </div>
                                        </div>
                                        <small class="form-text text-muted ml-1">
                                            Ubicación física asignada (no editable)
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <!-- Botones de acción -->
                            <div class="form-group mt-5 pt-4 border-top">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('materials.index') }}" class="btn btn-outline-secondary px-4 rounded-pill">
                                        <i class="fas fa-arrow-left mr-2"></i> Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-primary px-4 rounded-pill shadow-sm">
                                        <i class="fas fa-save mr-2"></i> Guardar Cambios
                                    </button>
                                </div>
                            </div>
                        </form>
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
        }

        .bg-gradient-primary {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        }

        .card {
            border-radius: 0.5rem;
            overflow: hidden;
            border: none;
        }

        .border-2 {
            border-width: 2px !important;
            border-color: #e0e0e0 !important;
        }

        .form-control, .form-select {
            transition: all 0.3s;
            border-radius: 0.375rem;
        }

        .form-control:focus, .form-select:focus {
            border-color: #4e73df !important;
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }

        .form-control.bg-light {
            background-color: #f8f9fa !important;
        }

        .btn-primary {
            background-color: #4e73df;
            border-color: #4e73df;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            background-color: #3d5dd9;
            transform: translateY(-2px);
        }

        .btn-outline-secondary:hover {
            background-color: #f8f9fa;
        }

        .rounded-pill {
            border-radius: 50rem !important;
        }

        .shadow-sm {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
        }

        .badge {
            font-weight: 500;
            letter-spacing: 0.5px;
        }

        .text-dark {
            color: #5a5c69 !important;
        }
    </style>
@endsection
