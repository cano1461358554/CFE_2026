@extends('layouts.app')

@section('template_title')
    {{ __('Papelera de Reciclaje - Stock') }}
@endsection

@section('content')
    <div class="container-fluid px-4">
        <div class="card border-0 shadow-lg">
            <div class="card-header bg-gradient-warning text-black py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">
                        <i class="fas fa-trash-restore mr-2"></i> Papelera de Reciclaje
                    </h3>
                    <div class="d-flex align-items-center">
                        <div class="badge bg-white text-warning p-2 mr-3">
                            <i class="fas fa-trash mr-1"></i>
                            Total: {{ $stocks->total() }} registros
                        </div>
                        <a href="{{ route('stocks.index') }}" class="btn btn-primary">
                            <i class="fas fa-arrow-left mr-2"></i> Volver a Stock
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                @if ($message = Session::get('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle mr-2"></i> {{ $message }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if ($message = Session::get('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle mr-2"></i> {{ $message }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <!-- Barra de herramientas -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <form method="GET" action="{{ route('stocks.trash') }}" class="flex-grow-1 mr-3">
                        <div class="input-group">
                            <input type="text" name="material" class="form-control"
                                   placeholder="Buscar en papelera..."
                                   value="{{ request('material') }}"
                                   aria-label="Buscar material">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-search"></i> Buscar
                                </button>
                                <a href="{{ route('stocks.trash') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-broom"></i> Limpiar
                                </a>
                            </div>
                        </div>
                    </form>

                    <div class="btn-group" role="group">
                        @if($stocks->total() > 0)
                            <form action="{{ route('stocks.trash.restore-all') }}" method="POST" class="mr-2">
                                @csrf
                                @method('POST')
                                <button type="submit" class="btn btn-success"
                                        onclick="return confirm('¿Restaurar todos los stocks de la papelera?')">
                                    <i class="fas fa-redo-alt mr-2"></i> Restaurar Todos
                                </button>
                            </form>

                            <form action="{{ route('stocks.trash.empty') }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger"
                                        onclick="return confirm('¿Está seguro? Esta acción eliminará permanentemente todos los stocks de la papelera y no se podrá deshacer.')">
                                    <i class="fas fa-trash-alt mr-2"></i> Vaciar Papelera
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                @if($stocks->total() > 0)
                    <div class="table-responsive rounded-lg">
                        <table class="table table-hover">
                            <thead class="thead-dark">
                            <tr>
                                <th class="text-center">Cantidad</th>
                                <th>Material</th>
                                <th>Almacén</th>
                                <th class="text-center">Eliminado el</th>
                                <th class="text-center" style="width: 200px;">Acciones</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($stocks as $stock)
                                <tr class="bg-light">
                                    <td class="text-center align-middle">
                                        <span class="badge badge-pill badge-secondary">
                                            {{ $stock->cantidad }}
                                        </span>
                                    </td>
                                    <td class="align-middle">
                                        @if($stock->material)
                                            <strong>{{ $stock->material->nombre }}</strong>
                                            <small class="d-block text-muted">{{ $stock->material->codigo }}</small>
                                        @else
                                            <strong class="text-danger">Material eliminado</strong>
                                            <small class="d-block text-muted">Código no disponible</small>
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        {{ $stock->almacen->nombre ?? 'N/A' }}
                                    </td>
                                    <td class="text-center align-middle">
                                        <small class="text-muted">
                                            {{ $stock->deleted_at->format('d/m/Y H:i') }}
                                        </small>
                                    </td>
                                    <td class="text-center align-middle">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <form action="{{ route('stocks.trash.restore', $stock->id) }}" method="POST" class="mr-1">
                                                @csrf
                                                @method('POST')
                                                <button type="submit" class="btn btn-success"
                                                        data-toggle="tooltip" title="Restaurar stock">
                                                    <i class="fas fa-redo"></i>
                                                </button>
                                            </form>

                                            <form action="{{ route('stocks.trash.force-delete', $stock->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger"
                                                        onclick="return confirm('¿Eliminar permanentemente este stock? Esta acción no se puede deshacer.')"
                                                        data-toggle="tooltip" title="Eliminar permanentemente">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    @if($stocks->hasPages())
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <p class="text-muted">
                                    Mostrando {{ $stocks->firstItem() }} a {{ $stocks->lastItem() }}
                                    de {{ $stocks->total() }} registros
                                </p>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex justify-content-end">
                                    {!! $stocks->withQueryString()->links() !!}
                                </div>
                            </div>
                        </div>
                    @endif
                @else
                    <div class="text-center py-5">
                        <div class="empty-state">
                            <i class="fas fa-trash-alt fa-4x text-muted mb-4"></i>
                            <h3 class="text-muted">Papelera vacía</h3>
                            <p class="text-muted mb-4">No hay stocks eliminados en la papelera de reciclaje.</p>
                            <a href="{{ route('stocks.index') }}" class="btn btn-primary">
                                <i class="fas fa-arrow-left mr-2"></i> Volver al Inventario
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        .bg-gradient-warning {
            background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
        }

        .empty-state {
            padding: 2rem 0;
            text-align: center;
        }

        .table th {
            border-top: none;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.5px;
            vertical-align: middle;
        }

        .table td {
            vertical-align: middle;
        }

        .badge-secondary {
            background-color: #6c757d;
        }

        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
        }

        .btn-success:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }

        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .btn-danger:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }

        .rounded-lg {
            border-radius: 0.5rem;
            overflow: hidden;
        }

        .thead-dark {
            background-color: #343a40;
            color: white;
        }
    </style>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Inicializar tooltips
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@endsection
