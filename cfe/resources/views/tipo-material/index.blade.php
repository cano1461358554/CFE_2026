{{-- resources/views/tipo-material/index.blade.php --}}
@extends('layouts.app')

@section('template_title')
    {{ __('Tipos de Material') }}
@endsection

@section('content')
    <div class="container">
        <div class="card shadow-lg">
            <div class="card-header text-center text-white" style="background-color: #00723E;">
                <h3 class="mb-0"><i class="fa fa-cubes"></i> {{ __('Gestión de Tipos de Material') }}</h3>
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
                <form method="GET" action="{{ route('tipo-materials.index') }}" class="mb-4">
                    <div class="row">
                        <div class="col-md-5">
                            <input type="text" name="descripcion" class="form-control" placeholder="Buscar tipo de material..." value="{{ request('descripcion') }}">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn text-white" style="background-color: #4CAF50;">
                                <i class="fa fa-search"></i> Buscar
                            </button>
                            <a href="{{ route('tipo-materials.index') }}" class="btn btn-warning">
                                <i class="fa fa-sync-alt"></i> Limpiar
                            </a>
                        </div>
                        <div class="col-md-4 text-end">
                            <a href="{{ route('tipo-materials.create') }}" class="btn text-white" style="background-color: #A4D65E;">
                                <i class="fa fa-plus-circle"></i> Nuevo Tipo
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
                            <th>Descripción</th>
                            <th>Tipo</th>
                            <th>Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($tipoMaterials as $tipo)
                            <tr>
                                <td class="text-center">{{ $tipo->id }}</td>
                                <td>
                                    <strong>{{ $tipo->descripcion_formateada ?? ucfirst(strtolower($tipo->descripcion)) }}</strong>
                                </td>
                                <td class="text-center">
                                    @if($tipo->protegida)
                                        <span class="badge bg-warning p-2">
                                                <i class="fa fa-shield-alt"></i> Sistema
                                            </span>
                                    @else
                                        <span class="badge bg-info p-2">
                                                <i class="fa fa-user-edit"></i> Personalizado
                                            </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    {{-- Botón Editar --}}
                                    <a href="{{ route('tipo-materials.edit', $tipo->id) }}" class="btn btn-warning btn-sm" title="Editar">
                                        <i class="fa fa-edit"></i> Editar
                                    </a>

                                    {{-- Botón Eliminar (solo para no protegidos) --}}
                                    @if(!$tipo->protegida)
                                        <form action="{{ route('tipo-materials.destroy', $tipo->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="return confirm('¿Está seguro de eliminar el tipo {{ $tipo->descripcion }}?')"
                                            title="Eliminar tipo de material">
                                            <i class="fa fa-trash"></i> Eliminar
                                            </button>
                                        </form>
                                    @else
                                        <button class="btn btn-secondary btn-sm" disabled title="Tipo del sistema - No se puede eliminar">
                                            <i class="fa fa-ban"></i> Eliminar
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-danger">
                                    <strong><i class="fa fa-exclamation-circle"></i> No hay tipos de material registrados.</strong>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                @if($tipoMaterials instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    <div class="d-flex justify-content-center mt-3">
                        {!! $tipoMaterials->withQueryString()->links() !!}
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
    </style>
@endsection
