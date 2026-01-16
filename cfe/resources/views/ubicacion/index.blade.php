@extends('layouts.app')

@section('template_title')
    {{ __('Ubicaciones') }}
@endsection

@section('content')
    <div class="container">
        <div class="card shadow-lg">
            <div class="card-header text-center text-white" style="background-color: #00723E;">
                <h3 class="mb-0"><i class="fa fa-map-marker-alt"></i> {{ __('Gestión de Ubicaciones') }}</h3>
            </div>

            <div class="card-body">
                <!-- Mensajes -->
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

                <!-- Formulario de búsqueda -->
                <form method="GET" action="{{ route('ubicacions.index') }}" class="mb-4">
                    <div class="row">
                        <div class="col-md-5">
                            <input type="text" name="ubicacion" class="form-control" placeholder="Buscar ubicación..." value="{{ request('ubicacion') }}">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn text-white" style="background-color: #4CAF50;">
                                <i class="fa fa-search"></i> Buscar
                            </button>
                            <a href="{{ route('ubicacions.index') }}" class="btn btn-warning">
                                <i class="fa fa-sync-alt"></i> Limpiar
                            </a>
                        </div>
                        <div class="col-md-4 text-end">
                            <a href="{{ route('ubicacions.create') }}" class="btn text-white" style="background-color: #A4D65E;">
                                <i class="fa fa-plus-circle"></i> Nueva Ubicación
                            </a>
                        </div>
                    </div>
                </form>

                <!-- Tabla -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="text-white text-center" style="background-color: #00723E;">
                        <tr>
                            <th>ID</th>
                            <th>Ubicación</th>
                            <th>Tipo</th>
                            <th>Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($ubicacions as $ubicacion)
                            <tr>
                                <td class="text-center">{{ $ubicacion->id }}</td>
                                <td>
                                    <strong>{{ $ubicacion->ubicacion_formateada ?? $ubicacion->ubicacion }}</strong>
                                </td>
                                <td class="text-center">
                                    @if($ubicacion->protegida)
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
                                    {{-- Botón Editar --}}
                                    <a href="{{ route('ubicacions.edit', $ubicacion->id) }}" class="btn btn-warning btn-sm" title="Editar">
                                        <i class="fa fa-edit"></i> Editar
                                    </a>

                                    {{-- Botón Eliminar (solo para no protegidas) --}}
                                    @if(!$ubicacion->protegida)
                                        <form action="{{ route('ubicacions.destroy', $ubicacion->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="return confirm('¿Está seguro de eliminar la ubicación {{ $ubicacion->ubicacion }}?')"
                                            title="Eliminar ubicación">
                                            <i class="fa fa-trash"></i> Eliminar
                                            </button>
                                        </form>
                                    @else
                                        <button class="btn btn-secondary btn-sm" disabled title="Ubicación del sistema - No se puede eliminar">
                                            <i class="fa fa-ban"></i> Eliminar
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-danger">
                                    <strong><i class="fa fa-exclamation-circle"></i> No hay ubicaciones registradas.</strong>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                @if($ubicacions instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    <div class="d-flex justify-content-center mt-3">
                        {!! $ubicacions->withQueryString()->links() !!}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Estilos -->
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
        td strong {
            color: #2c3e50;
        }
        .fa-map-marker-alt {
            color: #ff6b6b;
        }
    </style>
@endsection
