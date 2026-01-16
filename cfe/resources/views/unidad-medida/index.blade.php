@extends('layouts.app')

@section('template_title')
    {{ __('Unidades de Medida') }}
@endsection

@section('content')
    <div class="container">
        <div class="card shadow-lg">
            <div class="card-header text-center text-white" style="background-color: #00723E;">
                <h3 class="mb-0"><i class="fa fa-balance-scale"></i> {{ __('Gestión de Unidades de Medida') }}</h3>
            </div>

            <div class="card-body">
                <!-- Mensajes de éxito y error -->
                @if(session('error'))
                    <div class="alert alert-danger text-center">
                        <i class="fa fa-exclamation-triangle"></i> {{ session('error') }}
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success text-center">
                        <i class="fa fa-check-circle"></i> {{ session('success') }}
                    </div>
                @endif

                @if ($message = Session::get('success'))
                    <div class="alert alert-success text-center">
                        <p>{{ $message }}</p>
                    </div>
                @endif

                <!-- Formulario de búsqueda -->
                <form method="GET" action="{{ route('unidad-medidas.index') }}" class="mb-4">
                    <div class="row">
                        <div class="col-md-5">
                            <input type="text" name="descripcion_unidad" class="form-control" placeholder="Buscar unidad de medida..." value="{{ request('descripcion_unidad') }}">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn text-white" style="background-color: #4CAF50;">
                                <i class="fa fa-search"></i> Buscar
                            </button>
                            <a href="{{ route('unidad-medidas.index') }}" class="btn btn-warning">
                                <i class="fa fa-sync-alt"></i> Limpiar
                            </a>
                        </div>
                        <div class="col-md-4 text-end">
                            <a href="{{ route('unidad-medidas.create') }}" class="btn text-white" style="background-color: #A4D65E;">
                                <i class="fa fa-plus-circle"></i> Crear Nueva
                            </a>
                        </div>
                    </div>

                    <div class="form-check mt-3">
                        <input type="checkbox" class="form-check-input" id="mostrar_coincidencias" name="mostrar_coincidencias"
                               {{ request('mostrar_coincidencias') ? 'checked' : '' }}
                               onchange="this.form.submit()">
                        <label class="form-check-label" for="mostrar_coincidencias">Mostrar solo coincidencias</label>
                    </div>
                </form>

                <!-- Tabla de unidades -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="text-white text-center" style="background-color: #00723E;">
                        <tr>
                            <th>ID</th>
                            <th>Descripción de la Unidad</th>
                            <th>Tipo</th>
                            <th>Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php
                            $descripcionBuscada = request('descripcion_unidad');
                        @endphp

                        @forelse ($unidadMedidas as $unidadMedida)
                            @php
                                $coincide = $descripcionBuscada && stripos($unidadMedida->descripcion_unidad, $descripcionBuscada) !== false;
                            @endphp
                            <tr class="{{ $coincide ? 'table-success' : '' }}">
                                <td class="text-center">{{ $unidadMedida->id }}</td>
                                <td><strong>{{ $unidadMedida->descripcion_unidad }}</strong></td>
                                <td class="text-center">
                                    @if($unidadMedida->protegida)
                                        <span class="badge bg-warning p-2">
                                                <i class="fa fa-shield-alt"></i> Sistema
                                            </span>
                                    @else
                                        <span class="badge bg-info p-2">
                                                <i class="fa fa-user-edit"></i> Personalizada
                                            </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    {{-- Botón Ver (opcional) --}}
                                    {{-- <a href="{{ route('unidad-medidas.show', $unidadMedida->id) }}" class="btn btn-primary btn-sm" title="Ver detalles">
                                        <i class="fa fa-eye"></i>
                                    </a> --}}

                                    {{-- Botón Editar --}}
                                    <a href="{{ route('unidad-medidas.edit', $unidadMedida->id) }}" class="btn btn-warning btn-sm" title="Editar">
                                        <i class="fa fa-edit"></i> Editar
                                    </a>

                                    {{-- Botón Eliminar (solo para no protegidos) --}}
                                    @if(!$unidadMedida->protegida)
                                        <form action="{{ route('unidad-medidas.destroy', $unidadMedida->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="return confirm('¿Está seguro de eliminar la unidad \"{{ $unidadMedida->descripcion_unidad }}\"?')"
                                            title="Eliminar unidad">
                                            <i class="fa fa-trash"></i> Eliminar
                                            </button>
                                        </form>
                                    @else
                                        <button class="btn btn-secondary btn-sm" disabled title="Unidad del sistema - No se puede eliminar">
                                            <i class="fa fa-ban"></i> Eliminar
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-danger">
                                    <strong><i class="fa fa-exclamation-circle"></i> No hay unidades de medida registradas.</strong>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                <div class="d-flex justify-content-center mt-3">
                    {!! $unidadMedidas->withQueryString()->links() !!}
                </div>
            </div>
        </div>
    </div>

    <!-- Estilos adicionales -->
    <style>
        .badge {
            font-size: 0.9em;
            min-width: 100px;
        }
        .btn-sm {
            margin: 2px;
        }
        .table th {
            vertical-align: middle;
        }
        .alert {
            border-radius: 8px;
            border: none;
        }
        .table-hover tbody tr:hover {
            background-color: rgba(0, 114, 62, 0.05);
        }
    </style>
@endsection
