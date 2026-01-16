@extends('layouts.app')

@section('template_title')
    {{ __('Registro de Ingresos de Materiales') }}
@endsection

@section('content')
    <div class="container-fluid px-4">
        <div class="card border-0 shadow-lg">
            <!-- Encabezado con gradiente profesional -->
            <div class="card-header bg-gradient-primary text-black py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">
                        <i class="fas fa-sign-in-alt mr-2"></i> Gestión de Ingresos
                    </h3>
                    <div class="badge bg-white text-primary p-2">
                        <i class="fas fa-database mr-1"></i>
                        Total: {{ $ingresos->total() }} registros
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

                <!-- Barra de herramientas -->
                <div class="row mb-4">
                    <div class="col-md-8">
                        <form method="GET" action="{{ route('ingresos.index') }}" class="form-inline">
                            <div class="input-group w-100">
                                <input type="date" name="fecha" class="form-control"
                                       placeholder="Buscar por fecha..."
                                       value="{{ request('fecha') }}"
                                       aria-label="Buscar por fecha">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search"></i> Buscar
                                    </button>
                                    <a href="{{ route('ingresos.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-broom"></i> Limpiar
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                    @role('admin|encargado')
                    <div class="col-md-4 text-right">
                        <a href="{{ route('ingresos.create') }}" class="btn btn-success">
                            <i class="fas fa-plus-circle mr-2"></i> Nuevo Ingreso
                        </a>
                    </div>
                    @endrole
                </div>

                <!-- Tabla de ingresos -->
                <div class="table-responsive rounded">
                    <table class="table table-hover table-bordered">
                        <thead class="thead-light">
                        <tr>
                            <th class="text-center">Material</th>
                            <th class="text-center">Responsable</th>
                            <th class="text-center">Cantidad</th>
                            <th class="text-center">Fecha</th>
                            @role('admin|encargado')
                            <th class="text-center" style="width: 180px;">Acciones</th>
                            @endrole
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($ingresos as $ingreso)
                            <tr>
                                <td class="contextual" data-type="material" data-value="{{ $ingreso->material_id ?? '' }}">
                                    <strong>{{ $ingreso->material->nombre ?? 'Sin material' }}</strong>
                                    @if($ingreso->material)
                                        <br><small class="text-muted">{{ $ingreso->material->codigo }}</small>
                                    @endif
                                </td>
                                <td class="contextual" data-type="user" data-value="{{ $ingreso->user_id ?? '' }}">
                                    {{ $ingreso->user->nombre ?? 'Sin responsable' }}
                                </td>
                                <td class="text-center">{{ number_format($ingreso->cantidad_ingresada) }}</td>
                                <td class="text-center">{{ $ingreso->fecha->format('d/m/Y') }}</td>
                                @role('admin|encargado')
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('ingresos.edit', $ingreso->id) }}"
                                           class="btn btn-outline-warning"
                                           data-toggle="tooltip"
                                           title="Editar ingreso">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('ingresos.destroy', $ingreso->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="btn btn-outline-danger"
                                                    onclick="return confirm('¿Confirmas eliminar este ingreso?')"
                                                    data-toggle="tooltip"
                                                    title="Eliminar ingreso">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                                @endrole
                            </tr>
                        @empty
                            <tr>
                                <td colspan="@role('admin|encargado')5 @else 4 @endrole" class="text-center py-4">
                                    <div class="empty-state">
                                        <i class="fas fa-sign-in-alt fa-3x text-muted mb-3"></i>
                                        <h4 class="text-muted">No se encontraron ingresos</h4>
                                        @role('admin|encargado')
                                        <p class="text-muted">Puedes comenzar registrando un nuevo ingreso</p>
                                        <a href="{{ route('ingresos.create') }}" class="btn btn-primary mt-2">
                                            <i class="fas fa-plus"></i> Registrar Ingreso
                                        </a>
                                        @endrole
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                @if($ingresos->hasPages())
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <p class="text-muted">
                                Mostrando {{ $ingresos->firstItem() }} a {{ $ingresos->lastItem() }}
                                de {{ $ingresos->total() }} registros
                            </p>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-end">
                                {!! $ingresos->withQueryString()->links() !!}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Menú contextual mejorado -->
    <div id="context-menu" class="dropdown-menu shadow-lg" style="display: none; position: absolute; z-index: 1050;">
        <div class="px-3 py-2">
            <h6 class="dropdown-header text-primary" id="context-menu-title"></h6>
            <div class="px-2 mb-2">
                <input type="text" id="filter-search" class="form-control form-control-sm" placeholder="Buscar..." style="display: none;">
            </div>
            <div id="context-menu-content" style="max-height: 300px; overflow-y: auto;"></div>
            <div class="dropdown-divider"></div>
            <button id="show-all" class="dropdown-item text-primary">
                <i class="fas fa-list mr-2"></i> Mostrar todos
            </button>
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

        .empty-state {
            padding: 2rem 0;
            text-align: center;
        }

        .btn-outline-warning {
            color: #ffc107;
            border-color: #ffc107;
        }

        .btn-outline-warning:hover {
            background-color: #ffc107;
            color: #212529;
        }

        .btn-outline-danger {
            color: #dc3545;
            border-color: #dc3545;
        }

        .btn-outline-danger:hover {
            background-color: #dc3545;
            color: white;
        }

        .contextual {
            cursor: context-menu;
        }

        .contextual:hover {
            background-color: rgba(0, 114, 62, 0.05);
        }

        .rounded {
            border-radius: 0.5rem !important;
        }
    </style>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Inicializar tooltips
            $('[data-toggle="tooltip"]').tooltip();

            const contextCells = document.querySelectorAll('.contextual');
            const contextMenu = document.getElementById('context-menu');
            const contextMenuTitle = document.getElementById('context-menu-title');
            const contextMenuContent = document.getElementById('context-menu-content');
            const filterSearch = document.getElementById('filter-search');

            const columnTitles = {
                'material': 'Material',
                'user': 'Responsable'
            };

            document.addEventListener('click', function (event) {
                if (!contextMenu.contains(event.target)) {
                    contextMenu.style.display = 'none';
                }
            });

            contextCells.forEach(cell => {
                cell.addEventListener('contextmenu', function (event) {
                    event.preventDefault();

                    const column = this.cellIndex;
                    const table = this.closest('table');
                    const allRows = table.querySelectorAll('tbody tr');
                    const dataType = this.getAttribute('data-type');

                    const values = new Map();
                    allRows.forEach(row => {
                        const cell = row.cells[column];
                        if (cell) {
                            const val = cell.textContent.trim();
                            const id = cell.getAttribute('data-value');
                            if (val && !val.startsWith('Sin')) {
                                values.set(val, id);
                            }
                        }
                    });

                    // Configurar título del menú
                    contextMenuTitle.innerHTML = `<i class="fas fa-filter mr-1"></i> Filtrar por ${columnTitles[dataType]}`;

                    // Mostrar campo de búsqueda
                    filterSearch.style.display = 'block';
                    filterSearch.focus();

                    // Generar contenido del menú
                    let html = '<div class="list-group list-group-flush">';

                    // Ordenar valores alfabéticamente
                    const sortedValues = Array.from(values.keys()).sort((a, b) => a.localeCompare(b));

                    sortedValues.forEach(val => {
                        html += `
                        <a href="#" class="list-group-item list-group-item-action filter-link py-2"
                           data-column="${column}" data-value="${val}">
                           <i class="fas fa-tag mr-2 text-muted"></i> ${val}
                        </a>`;
                    });

                    html += '</div>';
                    contextMenuContent.innerHTML = html;

                    // Posicionar el menú
                    contextMenu.style.top = `${event.pageY}px`;
                    contextMenu.style.left = `${event.pageX}px`;
                    contextMenu.style.display = 'block';

                    // Configurar búsqueda en tiempo real
                    filterSearch.addEventListener('input', function() {
                        const searchValue = this.value.toLowerCase();
                        const items = contextMenuContent.querySelectorAll('.filter-link');

                        items.forEach(item => {
                            const text = item.textContent.toLowerCase();
                            item.style.display = text.includes(searchValue) ? '' : 'none';
                        });
                    });

                    // Configurar eventos de los filtros
                    document.querySelectorAll('.filter-link').forEach(link => {
                        link.addEventListener('click', function (e) {
                            e.preventDefault();
                            const filterValue = this.getAttribute('data-value');
                            const filterCol = parseInt(this.getAttribute('data-column'));

                            table.querySelectorAll('tbody tr').forEach(row => {
                                const cell = row.cells[filterCol];
                                if (cell && cell.textContent.trim() === filterValue) {
                                    row.style.display = '';
                                } else {
                                    row.style.display = 'none';
                                }
                            });

                            contextMenu.style.display = 'none';
                        });
                    });

                    // Configurar botón "Mostrar todos"
                    document.getElementById('show-all').addEventListener('click', function () {
                        table.querySelectorAll('tbody tr').forEach(row => {
                            row.style.display = '';
                        });
                        contextMenu.style.display = 'none';
                    });
                });
            });
        });
    </script>
@endsection
