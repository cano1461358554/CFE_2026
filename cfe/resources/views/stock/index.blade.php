@extends('layouts.app')

@section('template_title')
    {{ __('Gestión de Inventario') }}
@endsection

@section('content')
    <div class="container-fluid px-4">
        <div class="card border-0 shadow-lg">
            <div class="card-header bg-gradient-primary text-black py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">
                        <i class="fas fa-boxes mr-2"></i> Control de Inventario
                    </h3>
                    <div class="d-flex align-items-center">
                        <div class="badge bg-white text-primary p-2 mr-3">
                            <i class="fas fa-database mr-1"></i>
                            Total: {{ $stocks->total() }} registros
                        </div>
                        <!-- Botón Papelera con contador -->
                        <a href="{{ route('stocks.trash') }}" class="btn btn-warning">
                            <i class="fas fa-trash-restore mr-2"></i> Papelera
                            @php
                                $trashedCount = \App\Models\Stock::onlyTrashed()->count();
                            @endphp
                            @if($trashedCount > 0)
                                <span class="badge badge-danger badge-pill ml-1">{{ $trashedCount }}</span>
                            @endif
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

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <form method="GET" action="{{ route('stocks.index') }}" class="flex-grow-1 mr-3">
                        <div class="input-group">
                            <input type="text" name="material" class="form-control"
                                   placeholder="Buscar por material..."
                                   value="{{ request('material') }}"
                                   aria-label="Buscar material">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Buscar
                                </button>
                                <a href="{{ route('stocks.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-broom"></i> Limpiar
                                </a>
                            </div>
                        </div>
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox"
                                   id="mostrar_coincidencias" name="mostrar_coincidencias"
                                   {{ request('mostrar_coincidencias') ? 'checked' : '' }}
                                   onchange="this.form.submit()">
                            <label class="form-check-label" for="mostrar_coincidencias">
                                Mostrar solo coincidencias exactas
                            </label>
                        </div>
                    </form>
                </div>

                <div class="alert alert-info alert-dismissible fade show mb-4" role="alert">
                    <i class="fas fa-info-circle mr-2"></i>
                    <strong>Nota:</strong> El stock se gestiona exclusivamente a través de ingresos.
                    Para agregar o modificar cantidades, utilice la sección de
                    <a href="{{ route('ingresos.index') }}" class="alert-link">Ingresos</a>.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="table-responsive rounded-lg">
                    <table class="table table-hover">
                        <thead class="thead-light">
                        <tr>
                            <th class="text-center">Cantidad</th>
                            <th class="redirectable" data-url="{{ route('materials.index') }}">Material</th>
                            <th class="redirectable" data-url="{{ route('almacens.index') }}">Almacén</th>
                            <th class="text-center">Estado</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php $materialBuscado = request('material'); @endphp
                        @forelse ($stocks as $stock)
                            @php
                                $coincide = $materialBuscado && stripos($stock->material->nombre, $materialBuscado) !== false;
                            @endphp
                            <tr class="{{ $coincide ? 'highlight-row' : '' }}">
                                <td class="text-center align-middle">
                                    <span class="badge badge-pill badge-primary">
                                        {{ $stock->cantidad }}
                                    </span>
                                </td>
                                <td class="redirectable align-middle" data-url="{{ route('materials.index') }}">
                                    @if($stock->material)
                                        <strong>{{ $stock->material->nombre }}</strong>
                                        <small class="d-block text-muted">{{ $stock->material->codigo }}</small>
                                    @else
                                        <strong class="text-danger">Material eliminado</strong>
                                        <small class="d-block text-muted">Código no disponible</small>
                                    @endif
                                </td>
                                <td class="redirectable align-middle" data-url="{{ route('almacens.index') }}">
                                    {{ $stock->almacen->nombre }}
                                </td>
                                <td class="text-center align-middle">
                                    <span class="badge badge-success">
                                        <i class="fas fa-check-circle mr-1"></i> Activo
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4">
                                    <div class="empty-state">
                                        <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                                        <h4 class="text-muted">No se encontraron registros</h4>
                                        <p class="text-muted">Los stocks se actualizan automáticamente al registrar un ingreso.</p>
                                        <a href="{{ route('ingresos.create') }}" class="btn btn-primary mt-2">
                                            <i class="fas fa-plus"></i> Registrar Nuevo Ingreso
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

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
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        .bg-gradient-primary {
            background: linear-gradient(135deg, #00723E 0%, #00A86B 100%);
        }

        .highlight-row {
            background-color: rgba(0, 168, 107, 0.1) !important;
            border-left: 4px solid #00723E;
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

        .badge-primary {
            background-color: #00723E;
        }

        .btn-warning {
            background-color: #ffc107;
            border-color: #ffc107;
            color: #212529;
        }

        .btn-warning:hover {
            background-color: #e0a800;
            border-color: #d39e00;
            color: #212529;
        }

        .redirectable {
            cursor: context-menu;
            transition: background-color 0.2s;
        }

        .redirectable:hover {
            background-color: rgba(0, 114, 62, 0.05);
        }

        .rounded-lg {
            border-radius: 0.5rem;
            overflow: hidden;
        }
    </style>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Inicializar tooltips
            $('[data-toggle="tooltip"]').tooltip();

            // Configurar celdas redireccionables
            $('.redirectable').each(function() {
                const url = $(this).data('url');
                const type = $(this).text().trim().toLowerCase().includes('material') ? 'Materiales' : 'Almacenes';

                $(this).attr('title', 'Clic derecho para ir a ' + type);

                $(this).on('contextmenu', function(e) {
                    e.preventDefault();
                    window.location.href = url;
                });
            });
        });
    </script>
@endsection
