@extends('layouts.app')

@section('template_title')
    {{ __('Sistema de Gestión de Materiales') }}
@endsection

@section('content')
    <div class="container-fluid px-4">
        <div class="card border-0 shadow-lg">
            <div class="card-header bg-gradient-primary text-black">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">
                        <i class="fas fa-boxes mr-2"></i>
                        {{ __('Inventario de Materiales') }}
                    </h3>

                    <div class="badge bg-white text-primary p-2">
                        <i class="fas fa-database mr-1"></i>
                        Total: {{ $materials->total() }} registros

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

                <div class="row mb-4">
                    <div class="col-md-8">
                        <form method="GET" action="{{ route('materials.index') }}" class="form-inline">
                            <div class="input-group w-100">
                                <input type="text" name="nombre" class="form-control"
                                       placeholder="Buscar material por nombre..."
                                       value="{{ request('nombre') }}"
                                       aria-label="Buscar">
                                <div class="input-gr oup-append">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search"></i> Buscar
                                    </button>
                                    <a href="{{ route('materials.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-broom"></i> Limpiar
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-4 text-right">
                        @role('admin|encargado')
                        <a href="{{ route('materials.create') }}" class="btn btn-success">
                            <i class="fas fa-plus-circle"></i> Nuevo Material
                        </a>
{{--                        <a href="{{ route('material.import.form') }}" class="btn btn-primary" title="Importar materiales desde archivo CSV" data-bs-toggle="tooltip">--}}
{{--                            <i class="fas fa-file-import"></i> <span class="d-none d-md-inline">Importar</span>--}}
{{--                        </a>--}}

                        <a class="nav-item">
                            <button class="btn btn-primary" onclick="window.location='{{ route('materials.eliminados') }}'">
                                <i class="fas fa-trash-restore"></i> Materiales Eliminados
                            </button>
                        </a>
                        @endrole
                    </div>
                </div>

                <div class="table-responsive rounded">
                    <table class="table table-hover table-bordered">
                        <thead class="thead-light">
                        <tr>
                            <th class="text-center">{{ __('Clave') }}</th>
                            <th>{{ __('Nombre') }}</th>
                            <th>{{ __('Marca') }}</th>
                            <th class="contextual" data-type="almacen">{{ __('Almacén') }}</th>
                            <th class="contextual" data-type="estante">{{ __('Estante') }}</th>
                            <th class="contextual" data-type="categoria">{{ __('Categoría') }}</th>
                            <th class="contextual" data-type="tipo-material">{{ __('Tipo') }}</th>
                            <th class="contextual" data-type="unidad-medida">{{ __('Unidad') }}</th>
                            <th>{{ __('descripcion') }}</th>

                            <th class="text-center" style="width: 150px;">{{ __('Acciones') }}</th>
{{--                            @role('admin|encargado')--}}
{{--                           --}}
{{--                            @endrole--}}
                        </tr>
                        </thead>
                        <tbody>
                        @php
                            $i = ($materials->currentPage() - 1) * $materials->perPage();
                            $nombreBuscado = request('nombre');
                        @endphp
                        @forelse ($materials as $material)
                            @php
                                $coincide = $nombreBuscado && stripos($material->nombre, $nombreBuscado) !== false;
                            @endphp
                            <tr class="{{ $coincide ? 'table-info' : '' }}">
                                <td class="text-center font-weight-bold">{{ $material->clave }}</td>
                                <td class="contextual" data-type="nombre">{{ $material->nombre }}</td>
                                <td class="contextual" data-type="marca">{{ $material->marca }}</td>
                                <td class="contextual" data-type="almacen" data-value="{{ $material->almacen?->id }}">
                                        <span class="badge badge-pill badge-secondary">
                                            {{ $material->almacen?->nombre ?? 'N/A' }}
                                        </span>
                                </td>
                                <td class="contextual" data-type="estante" data-value="{{ $material->estante }}">
                                    {{ $material->estante ?? 'N/A' }}
                                </td>
                                <td class="contextual" data-type="categoria" data-value="{{ $material->categoria?->id }}">
                                    {{ $material->categoria?->nombre ?? 'N/A' }}
                                </td>
                                <td class="contextual" data-type="tipo-material" data-value="{{ $material->tipomaterial?->id }}">
                                    {{ $material->tipomaterial?->descripcion ?? 'N/A' }}
                                </td>
                                <td class="contextual" data-type="unidad-medida" data-value="{{ $material->unidadmedida?->id }}">
                                    {{ $material->unidadmedida?->descripcion_unidad ?? 'N/A' }}
                                </td>
                                <td class="text-center font-weight-bold">{{ $material->descripcion }}</td>

                                {{--                                @role('admin|encargado')--}}
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button class="btn btn-outline-info stock-btn"
                                                data-material-id="{{ $material->id }}"
                                                data-material-name="{{ $material->nombre }}"
                                                data-toggle="tooltip"
                                                title="Ver stock">
                                            <i class="fas fa-boxes"></i>
                                        </button>
                                        @role('admin|encargado')
                                        <a href="{{ route('materials.edit', $material->id) }}"
                                           class="btn btn-outline-warning"
                                           data-toggle="tooltip"
                                           title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('materials.destroy', $material->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="btn btn-outline-danger"
                                                    onclick="return confirm('¿Confirmas eliminar este material?')"
                                                    data-toggle="tooltip"
                                                    title="Eliminar">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                                @endrole
                            </tr>
                        @empty
                            <tr>
                                <td colspan="@role('admin|encargado')9 @else 8 @endrole" class="text-center py-4">
                                    <div class="empty-state">
                                        <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                                        <h4 class="text-muted">No se encontraron materiales</h4>
                                        @role('admin|encargado')
                                        <p class="text-muted">Puedes comenzar agregando un nuevo material</p>
                                        <a href="{{ route('materials.create') }}" class="btn btn-primary mt-2">
                                            <i class="fas fa-plus"></i> Agregar Material
                                        </a>
                                        @endrole
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                @if($materials->hasPages())
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <p class="text-muted">
                                Mostrando {{ $materials->firstItem() }} a {{ $materials->lastItem() }}
                                de {{ $materials->total() }} registros
                            </p>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-end">
                                <nav aria-label="Page navigation">
                                    <ul class="pagination">
                                        {{-- Previous Page Link --}}
                                        @if ($materials->onFirstPage())
                                            <li class="page-item disabled" aria-disabled="true">
                                                <span class="page-link">&laquo; Anterior</span>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $materials->previousPageUrl() }}" rel="prev">&laquo; Anterior</a>
                                            </li>
                                        @endif

                                        {{-- Pagination Elements --}}
                                        @foreach ($materials->getUrlRange(1, $materials->lastPage()) as $page => $url)
                                            @if ($page == $materials->currentPage())
                                                <li class="page-item active" aria-current="page">
                                                    <span class="page-link">{{ $page }}</span>
                                                </li>
                                            @else
                                                <li class="page-item">
                                                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                                </li>
                                            @endif
                                        @endforeach

                                        {{-- Next Page Link --}}
                                        @if ($materials->hasMorePages())
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $materials->nextPageUrl() }}" rel="next">Siguiente &raquo;</a>
                                            </li>
                                        @else
                                            <li class="page-item disabled" aria-disabled="true">
                                                <span class="page-link">Siguiente &raquo;</span>
                                            </li>
                                        @endif
                                    </ul>
                                </nav>
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

    <!-- Modal para mostrar el stock -->
    <div class="modal fade" id="stockModal" tabindex="-1" role="dialog" aria-labelledby="stockModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="stockModalLabel">Stock Disponible</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p id="stockMessage"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

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
                'almacen': 'Almacén',
                'estante': 'Estante',
                'categoria': 'Categoría',
                'tipo-material': 'Tipo de Material',
                'unidad-medida': 'Unidad de Medida',
                'nombre': 'Nombre',
                'marca': 'Marca'
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
                            const val = cell.innerText.trim();
                            const id = cell.getAttribute('data-value');
                            if (val && !['N/A', 'Sin almacén', 'Sin estante', 'Sin categoría', 'Sin tipo', 'Sin unidad'].includes(val)) {
                                values.set(val, id);
                            }
                        }
                    });

                    // Configurar título del menú
                    contextMenuTitle.innerHTML = `<i class="fas fa-filter mr-1"></i> Filtrar por ${columnTitles[dataType]}`;

                    // Mostrar u ocultar campo de búsqueda
                    filterSearch.style.display = dataType !== 'estante' ? 'block' : 'none';
                    if (dataType !== 'estante') {
                        filterSearch.focus();
                    }

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
                    if (dataType !== 'estante') {
                        filterSearch.addEventListener('input', function() {
                            const searchValue = this.value.toLowerCase();
                            const items = contextMenuContent.querySelectorAll('.filter-link');

                            items.forEach(item => {
                                const text = item.textContent.toLowerCase();
                                item.style.display = text.includes(searchValue) ? '' : 'none';
                            });
                        });
                    }

                    // Configurar eventos de los filtros
                    document.querySelectorAll('.filter-link').forEach(link => {
                        link.addEventListener('click', function (e) {
                            e.preventDefault();
                            const filterValue = this.getAttribute('data-value');
                            const filterCol = parseInt(this.getAttribute('data-column'));

                            table.querySelectorAll('tbody tr').forEach(row => {
                                const cell = row.cells[filterCol];
                                if (!cell || cell.innerText.trim() !== filterValue) {
                                    row.style.display = 'none';
                                } else {
                                    row.style.display = '';
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

            // Manejar clic en botones de stock
            document.querySelectorAll('.stock-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const materialId = this.getAttribute('data-material-id');
                    const materialName = this.getAttribute('data-material-name');

                    fetch(`/materials/${materialId}/stock`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Error en la respuesta del servidor');
                            }
                            return response.json();
                        })
                        .then(data => {
                            document.getElementById('stockMessage').textContent =
                                `El stock disponible para ${materialName} es de ${data.stock} ${data.unidad}.`;
                            $('#stockModal').modal('show');
                        })
                        .catch(error => {
                            document.getElementById('stockMessage').textContent =
                                `Error al obtener el stock para ${materialName}. Detalle: ${error.message}`;
                            $('#stockModal').modal('show');
                            console.error('Error:', error);
                        });
                });
            });
        });
    </script>

    <style>
        body {
            background-color: #f8fafc;
        }

        .card {
            border-radius: 0.5rem;
            overflow: hidden;
        }

        .card-header {
            border-radius: 0 !important;
        }

        .table {
            margin-bottom: 0;
        }

        .table thead th {
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

        #context-menu {
            width: 280px;
            border: none;
            border-radius: 0.5rem;
        }

        #context-menu .dropdown-header {
            font-size: 0.8rem;
            font-weight: 600;
        }

        #context-menu .list-group-item {
            border: none;
            border-radius: 0.25rem;
            margin-bottom: 0.25rem;
            font-size: 0.85rem;
            transition: all 0.2s;
        }

        #context-menu .list-group-item:hover {
            background-color: #f1f8ff;
            color: #0056b3;
        }

        .contextual {
            cursor: context-menu;
            transition: background-color 0.2s;
        }

        .contextual:hover {
            background-color: rgba(0, 114, 62, 0.05);
        }

        .page-item.active .page-link {
            background-color: #00723E;
            border-color: #00723E;
        }

        .page-link {
            color: #00723E;
        }

        .badge-secondary {
            background-color: #6c757d;
            color: white;
        }
    </style>
@endsection
