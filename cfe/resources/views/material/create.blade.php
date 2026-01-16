@extends('layouts.app')

@section('template_title')
    {{ __('Registro de Nuevo Material') }}
@endsection

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-lg">
                    <!-- Encabezado con gradiente profesional -->
                    <div class="card-header bg-gradient-primary text-black py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="mb-0">
                                <i class="fas fa-box-open mr-2"></i> Nuevo Material
                            </h3>
                            <span class="badge bg-white text-primary py-2 px-3">
                            <i class="fas fa-plus-circle mr-1"></i> Nuevo Registro
                        </span>
                        </div>
                    </div>

                    <!-- Cuerpo del formulario -->
                    <div class="card-body p-4">
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <h5 class="alert-heading">
                                    <i class="fas fa-exclamation-triangle mr-1"></i> Error de validación
                                </h5>
                                <ul class="mb-0 pl-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('materials.store') }}" role="form">
                            @csrf

                            <div class="row">
                                <!-- Columna Izquierda -->
                                <div class="col-md-6 pr-lg-4">
                                    <div class="form-group mb-4">
                                        <label class="font-weight-bold text-dark">
                                            <i class="fas fa-key text-primary mr-1"></i> Clave Identificadora
                                        </label>
                                        <div class="input-group">
                                            <input type="text" class="form-control border-2 bg-light"
                                                   value="Generada automáticamente" readonly>
                                            <div class="input-group-append">
                                            <span class="input-group-text bg-light border-2">
                                                <i class="fas fa-lock text-muted"></i>
                                            </span>
                                            </div>
                                        </div>
                                        <small class="form-text text-muted ml-1">
                                            El sistema generará automáticamente la clave
                                        </small>
                                    </div>

                                    <div class="form-group mb-4">
                                        <label class="font-weight-bold text-dark">
                                            <i class="fas fa-tag text-primary mr-1"></i> Nombre del Material <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="nombre" id="nombre"
                                               class="form-control border-2 @error('nombre') is-invalid @enderror"
                                               value="{{ old('nombre') }}" required
                                               placeholder="Ingrese el nombre completo del material">
                                        @error('nombre')
                                        <div class="invalid-feedback d-block">
                                            <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-4">
                                        <label class="font-weight-bold text-dark">
                                            <i class="fas fa-certificate text-primary mr-1"></i> Marca <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="marca" id="marca"
                                               class="form-control border-2 @error('marca') is-invalid @enderror"
                                               value="{{ old('marca') }}" required
                                               placeholder="Ej. 3M, Bosch, Dell">
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
                                        <textarea name="descripcion" id="descripcion"
                                                  class="form-control border-2 @error('descripcion') is-invalid @enderror"
                                                  rows="3"
                                                  placeholder="Describa características importantes del material">{{ old('descripcion') }}</textarea>
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
                                            <i class="fas fa-warehouse text-primary mr-1"></i> Almacén <span class="text-danger">*</span>
                                        </label>
                                        <select name="almacen_id" id="almacen_id"
                                                class="form-control border-2 @error('almacen_id') is-invalid @enderror" required>
                                            <option value="">Seleccione un almacén...</option>
                                            @foreach ($almacens as $almacen)
                                                <option value="{{ $almacen->id }}" {{ old('almacen_id') == $almacen->id ? 'selected' : '' }}>
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

                                    <div class="form-group mb-4">
                                        <label class="font-weight-bold text-dark">
                                            <i class="fas fa-map-marker-alt text-primary mr-1"></i> Estante <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="estante" id="estante"
                                               class="form-control border-2 @error('estante') is-invalid @enderror"
                                               value="{{ old('estante') }}" required
                                               placeholder="Ej. A-12, B-5, etc.">
                                        @error('estante')
                                        <div class="invalid-feedback d-block">
                                            <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-4">
                                        <label class="font-weight-bold text-dark">
                                            <i class="fas fa-tags text-primary mr-1"></i> Categoría <span class="text-danger">*</span>
                                        </label>
                                        <select name="categoria_id" id="categoria_id"
                                                class="form-control border-2 @error('categoria_id') is-invalid @enderror" required>
                                            <option value="">Seleccione una categoría...</option>
                                            @foreach ($categorias as $categoria)
                                                <option value="{{ $categoria->id }}" {{ old('categoria_id') == $categoria->id ? 'selected' : '' }}>
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
                                        <select name="tipomaterial_id" id="tipomaterial_id"
                                                class="form-control border-2 @error('tipomaterial_id') is-invalid @enderror" required>
                                            <option value="">Seleccione un tipo...</option>
                                            @foreach ($tiposMaterial as $tipoMaterial)
                                                <option value="{{ $tipoMaterial->id }}" {{ old('tipomaterial_id') == $tipoMaterial->id ? 'selected' : '' }}>
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

                                    <div class="form-group">
                                        <label class="font-weight-bold text-dark">
                                            <i class="fas fa-ruler-combined text-primary mr-1"></i> Unidad de Medida <span class="text-danger">*</span>
                                        </label>
                                        <select name="unidadmedida_id" id="unidadmedida_id"
                                                class="form-control border-2 @error('unidadmedida_id') is-invalid @enderror" required>
                                            <option value="">Seleccione una unidad...</option>
                                            @foreach ($unidadesMedida as $unidadMedida)
                                                <option value="{{ $unidadMedida->id }}" {{ old('unidadmedida_id') == $unidadMedida->id ? 'selected' : '' }}>
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
                                </div>
                            </div>

                            <!-- Botones de acción -->
                            <div class="form-group mt-5 pt-4 border-top">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('materials.index') }}" class="btn btn-outline-secondary px-4">
                                        <i class="fas fa-times mr-2"></i> Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-primary px-4 shadow-sm">
                                        <i class="fas fa-save mr-2"></i> Registrar Material
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
            background: linear-gradient(135deg, #2c3e50 0%, #4b6cb7 100%);
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
            border-color: #4b6cb7 !important;
            box-shadow: 0 0 0 0.2rem rgba(75, 108, 183, 0.25);
        }

        .form-control.bg-light {
            background-color: #f8f9fa !important;
        }

        .btn-primary {
            background-color: #4b6cb7;
            border-color: #4b6cb7;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            background-color: #3a5a9c;
            transform: translateY(-2px);
        }

        .btn-outline-secondary:hover {
            background-color: #f8f9fa;
        }

        .shadow-sm {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
        }

        .badge {
            font-weight: 500;
            letter-spacing: 0.5px;
        }

        .text-dark {
            color: #2c3e50 !important;
        }

        .alert-danger {
            background-color: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
        }
    </style>
@endsection
