@extends('layouts.app')

@section('template_title')
    {{ __('Actualización de Almacén') }} - {{ $almacen->nombre }}
@endsection

@section('content')
    <div class="container-fluid px-4">
        <div class="card border-0 shadow-lg">
            <!-- Encabezado con gradiente profesional -->
            <div class="card-header bg-gradient-primary text-black py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">
                        <i class="fas fa-warehouse mr-2"></i> Editar Almacén
                    </h3>
                    <a href="{{ route('almacens.index') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-left mr-1"></i> Volver al listado
                    </a>
                </div>
            </div>

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

                <form method="POST" action="{{ route('almacens.update', $almacen->id) }}" class="needs-validation" novalidate>
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <!-- Columna Izquierda - Información Básica -->
                        <div class="col-lg-6">
                            <div class="card mb-4 border-0 shadow-sm">
                                <div class="card-header bg-white">
                                    <h5 class="mb-0 text-primary">
                                        <i class="fas fa-info-circle mr-2"></i> Información Básica
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="form-group mb-4">
                                        <label class="font-weight-bold text-dark">
                                            <i class="fas fa-signature mr-1 text-primary"></i> Nombre del Almacén <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control @error('nombre') is-invalid @enderror"
                                               name="nombre" value="{{ old('nombre', $almacen->nombre) }}" required
                                               placeholder="Ingrese el nombre completo del almacén">
                                        @error('nombre')
                                        <div class="invalid-feedback d-block">
                                            <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Columna Derecha - Ubicación -->
                        <div class="col-lg-6">
                            <div class="card mb-4 border-0 shadow-sm">
                                <div class="card-header bg-white">
                                    <h5 class="mb-0 text-primary">
                                        <i class="fas fa-map-marked-alt mr-2"></i> Ubicación Física
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label class="font-weight-bold text-dark">
                                            <i class="fas fa-location-dot mr-1 text-primary"></i> Ubicación <span class="text-danger">*</span>
                                        </label>

                                        @if($ubicacions->isEmpty())
                                            <div class="alert alert-warning">
                                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                                No hay ubicaciones disponibles.
                                                <a href="{{ route('ubicacions.create') }}" class="alert-link font-weight-bold">
                                                    Crear nueva ubicación
                                                </a>
                                            </div>
                                        @else
                                            <select name="ubicacion_id" id="ubicacion_id"
                                                    class="form-control @error('ubicacion_id') is-invalid @enderror" required>
                                                <option value="">Seleccione una ubicación...</option>
                                                @foreach($ubicacions as $ubicacion)
                                                    <option value="{{ $ubicacion->id }}"
                                                            @selected(old('ubicacion_id', $almacen->ubicacion_id) == $ubicacion->id)
                                                            data-content="<span class='text-dark'>{{ $ubicacion->nombre }} @if($almacen->ubicacion_id == $ubicacion->id) <span class='badge badge-success'>Actual</span> @endif</span>">
                                                        {{ $ubicacion->nombre }}
                                                        @if($almacen->ubicacion_id == $ubicacion->id) (Actual) @endif
                                                    </option>
                                                @endforeach
                                            </select>

                                            <small class="form-text text-muted">
                                                Ubicación actual: <strong>{{ $almacen->ubicacion->nombre ?? 'No asignada' }}</strong>
                                            </small>

                                            @error('ubicacion_id')
                                            <div class="invalid-feedback d-block">
                                                <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                            </div>
                                            @enderror
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botones de acción -->
                    <div class="form-group mt-4 pt-3 border-top">
                        <div class="d-flex justify-content-between">
                            <button type="reset" class="btn btn-outline-secondary px-4">
                                <i class="fas fa-undo mr-2"></i> Restablecer
                            </button>
                            <button type="submit" class="btn btn-primary px-4 shadow-sm">
                                <i class="fas fa-save mr-2"></i> Actualizar Almacén
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        .bg-gradient-primary {
            background: linear-gradient(135deg, #00723E 0%, #00A86B 100%);
        }

        .card {
            border-radius: 0.5rem;
            overflow: hidden;
        }

        .form-control, .form-select {
            border-radius: 0.375rem;
            transition: all 0.3s;
        }

        .form-control:focus, .form-select:focus {
            border-color: #00723E;
            box-shadow: 0 0 0 0.2rem rgba(0, 114, 62, 0.25);
        }

        .btn-primary {
            background-color: #00723E;
            border-color: #00723E;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            background-color: #005a32;
            transform: translateY(-2px);
        }

        .btn-outline-secondary:hover {
            background-color: #f8f9fa;
        }

        .alert-warning {
            background-color: #fff3cd;
            border-color: #ffeeba;
        }

        .badge-success {
            background-color: #28a745;
        }

        .text-dark {
            color: #343a40 !important;
        }
    </style>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <script>
        // Validación del formulario
        (function() {
            'use strict';
            const forms = document.querySelectorAll('.needs-validation');
            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        })();

        // Inicializar Select2
        $(document).ready(function() {
            $('#ubicacion_id').select2({
                placeholder: "Buscar ubicación...",
                allowClear: true,
                templateResult: formatUbicacion,
                templateSelection: formatUbicacion,
                width: '100%'
            });

            function formatUbicacion(ubicacion) {
                if (!ubicacion.id) return ubicacion.text;
                return $(ubicacion.element).data('content');
            }
        });
    </script>
@endsection
