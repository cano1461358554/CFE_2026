<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CFE Sistema de Inventario</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8fafc;
            padding-top: 70px;
        }

        /* Barra de navegación superior */
        .navbar-cfe {
            background-color: #00573F;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
        }

        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }

        .nav-link {
            color: #FFFFFF !important;
            padding: 10px 15px;
            transition: background-color 0.3s ease;
        }

        .nav-link:hover {
            background-color: #003D2C;
            border-radius: 4px;
        }

        .dropdown-menu {
            background-color: #007A5E;
            border: none;
        }

        .dropdown-item {
            color: #FFFFFF;
        }

        .dropdown-item:hover {
            background-color: #00573F;
        }

        /* Contenido principal */
        .content {
            padding: 20px;
            background-color: #FFFFFF;
            min-height: calc(100vh - 70px);
        }

        /* Estilos para tarjetas */
        .card {
            border-radius: 0.5rem;
            overflow: hidden;
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        .card-header {
            border-radius: 0 !important;
            background-color: #00723E;
            color: white;
        }

        /* Estilos para las tablas */
        .table {
            margin-bottom: 0;
            border-collapse: separate;
            border-spacing: 0;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .table thead th {
            border-top: none;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.5px;
            vertical-align: middle;
            background-color: #00723E;
            color: #FFFFFF;
        }

        .table td {
            vertical-align: middle;
        }

        .table tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .table tbody tr:hover {
            background-color: #e9ecef;
        }

        /* Estilos para botones */
        .btn-primary {
            background-color: #00723E;
            border-color: #00723E;
        }

        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
        }

        .btn-outline-secondary {
            color: #6c757d;
            border-color: #6c757d;
        }

        .btn-outline-secondary:hover {
            background-color: #6c757d;
            color: white;
        }

        /* Estilos para paginación */
        .page-item.active .page-link {
            background-color: #00723E;
            border-color: #00723E;
        }

        .page-link {
            color: #00723E;
        }

        /* Estilos para badges */
        .badge-success {
            background-color: #28a745;
        }

        .badge-warning {
            background-color: #ffc107;
            color: #212529;
        }

        /* Menú contextual */
        .contextual {
            cursor: context-menu;
            transition: background-color 0.2s;
        }

        .contextual:hover {
            background-color: rgba(0, 114, 62, 0.05);
        }

        #context-menu {
            display: none;
            position: absolute;
            z-index: 1050;
            width: 280px;
            border: none;
            border-radius: 0.5rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        #context-menu .dropdown-header {
            font-size: 0.8rem;
            font-weight: 600;
            color: #00723E;
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

        /* Estilos para el modal */
        .modal-header {
            background-color: #00723E;
            color: white;
        }

        .close {
            color: white;
            opacity: 1;
        }

        /* Estilos para alertas */
        .alert {
            border-radius: 0.25rem;
        }

        /* Estilos para el menú hamburguesa en móviles */
        .navbar-toggler {
            border-color: rgba(255,255,255,0.5);
        }

        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml;charset=utf8,%3Csvg viewBox='0 0 30 30' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath stroke='rgba(255, 255, 255, 0.8)' stroke-width='2' stroke-linecap='round' stroke-miterlimit='10' d='M4 7h22M4 15h22M4 23h22'/%3E%3C/svg%3E");
        }

        /* Estado vacío */
        .empty-state {
            padding: 2rem 0;
            text-align: center;
        }

        .empty-state i {
            font-size: 3rem;
            color: #6c757d;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>

<!-- Barra de navegación superior -->
<nav class="navbar navbar-expand-lg navbar-dark navbar-cfe">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">CFE Inventario</a>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarContent">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav mr-auto">
                <!-- Menú Movimientos -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="movimientosDropdown" data-toggle="dropdown">
                        <i class="fas fa-exchange-alt"></i> Movimientos
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="prestamos">Préstamos</a>
                        @role('admin|encargado')
                        <a class="dropdown-item" href="ingresos">Ingresos</a>
                        @endrole

                    </div>
                </li>

                <!-- Menú Materiales -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="materialesDropdown" data-toggle="dropdown">
                        <i class="fas fa-boxes"></i> Materiales
                    </a>
                    <div class="dropdown-menu">
                        @role('admin|encargado')
                        <a class="dropdown-item" href="almacens">Almacenes</a>
                        @endrole
                        <a class="dropdown-item" href="materials">Materiales</a>
                        @role('admin|encargado')
                        <a class="dropdown-item" href="stocks">Stocks</a>
                        @endrole
                    </div>
                </li>

                <!-- Menú Tipos -->
                @role('admin|encargado')
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="tiposDropdown" data-toggle="dropdown">
                        <i class="fas fa-tags"></i> Tipos
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="users">Usuarios</a>
                    </div>
                </li>
                @endrole
            </ul>

            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('logout') }}" onclick="confirmLogout(event)">
                        <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Contenido principal -->
<div class="content">
    <div class="container-fluid px-4">
        <div class="card shadow-lg">
            <div class="card-header bg-gradient-primary text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">
                        <i class="fas fa-hand-holding-usd mr-2"></i>
                        {{ __('Gestión de Préstamos') }}
                    </h3>
                    <div class="badge bg-white text-primary p-2">
                        <i class="fas fa-database mr-1"></i>
                        Total: {{ $prestamos->total() }} registros
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

                <!-- Filtro de búsqueda -->
                <div class="row mb-4">
                    <div class="col-md-8">
                        <form method="GET" action="{{ route('prestamos.index') }}" class="form-inline">
                            <div class="input-group w-100">
                                <input type="text" name="persona" class="form-control"
                                       placeholder="Buscar por nombre o apellido..."
                                       value="{{ request('persona') }}"
                                       aria-label="Buscar">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search"></i> Buscar
                                    </button>
                                    <a href="{{ route('prestamos.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-broom"></i> Limpiar
                                    </a>
                                </div>
                            </div>
                            <div class="form-check mt-2 ml-2">
                                <input type="checkbox" class="form-check-input" id="mostrar_coincidencias" name="mostrar_coincidencias"
                                       {{ request('mostrar_coincidencias') ? 'checked' : '' }}
                                       onchange="this.form.submit()">
                                <label class="form-check-label" for="mostrar_coincidencias">Mostrar solo coincidencias</label>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-4 text-right">
                        @role('admin|encargado')
                        <a href="{{ route('prestamos.create') }}" class="btn btn-success">
                            <i class="fas fa-plus-circle"></i> Nuevo Préstamo
                        </a>
                        @endrole
                    </div>
                </div>

                <!-- tabla de préstamos -->
                <div class="table-responsive rounded">

                    <table class="table table-hover table-bordered">
                        <thead class="thead-light">
                        <tr>
                            <th>{{ __('Fecha Préstamo') }}</th>
                            <th>{{ __('Cantidad Prestada') }}</th>
                            <th>{{ __('Cantidad Devuelta') }}</th>
                            <th>{{ __('Pendiente') }}</th>
                            <th class="contextual" data-type="estado">{{ __('Estado') }}</th>
                            <th class="contextual" data-type="material">{{ __('Material') }}</th>
                            <th class="contextual" data-type="user">{{ __('Personal') }}</th>
                            <th>{{ __('Descripción') }}</th>
                            @role('admin|encargado')
                            <th class="text-center" style="width: 150px;">{{ __('Acciones') }}</th>
                            @endrole
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($prestamos as $prestamo)
                            @php
                                $cantidadDevuelta = $prestamo->devolucions->sum('cantidad_devuelta');
                                $pendiente = $prestamo->cantidad_prestada - $cantidadDevuelta;
                                $completamenteDevuelto = $pendiente <= 0;
                                $coincide = request('persona') &&
                                    (stripos($prestamo->user->nombre ?? '', request('persona')) !== false ||
                                     stripos($prestamo->user->apellido ?? '', request('persona')) !== false);
                            @endphp

                            <tr class="{{ $completamenteDevuelto ? 'table-success' : '' }} {{ $coincide ? 'table-info' : '' }}">
                                <td>{{ $prestamo->fecha_prestamo }}</td>
                                <td>{{ $prestamo->cantidad_prestada }}</td>
                                <td>{{ $cantidadDevuelta }}</td>
                                <td>{{ $pendiente }}</td>
                                <td class="contextual" data-type="estado">
                                    @if($completamenteDevuelto)
                                        <span class="badge badge-success">Entregado</span>
                                    @else
                                        <span class="badge badge-warning">Pendiente</span>
                                    @endif
                                </td>
                                <td class="contextual" data-type="material" data-url="{{ route('materials.index') }}">
                                    {{ $prestamo->material->nombre ?? 'Material eliminado' }}
                                    @if(isset($prestamo->material) && $prestamo->material->trashed())
                                        <span class="badge badge-warning">Eliminado</span>
                                    @endif
                                </td>
                                <td class="contextual" data-type="user" data-url="{{ route('users.index') }}">
                                    {{ $prestamo->user->nombre ?? '' }} {{ $prestamo->user->apellido ?? '' }}
                                </td>
                                <td>{{ $prestamo->descripcion }}</td>
                                @role('admin|encargado')
                                <td class="text-center">
                                    @if(!$completamenteDevuelto)
                                        <button class="btn btn-info btn-sm" data-toggle="modal"
                                                data-target="#devolucionModal"
                                                data-prestamo-id="{{ $prestamo->id }}"
                                                data-material="{{ $prestamo->material->nombre ?? 'Sin material' }}"
                                                data-cantidad="{{ $pendiente }}"
                                                data-user="{{ $prestamo->user->nombre ?? 'Sin personal' }} {{ $prestamo->user->apellido ?? '' }}"
                                                data-descripcion="{{ $prestamo->descripcion }}">
                                            <i class="fa fa-undo"></i> {{ __('Devolver') }}
                                        </button>
                                    @endif
                                </td>
                                @endrole
                            </tr>
                        @empty
                            <tr>
                                <td colspan="@role('admin|encargado')9 @else 8 @endrole" class="text-center py-4">
                                    <div class="empty-state">
                                        <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                                        <h4 class="text-muted">{{ __('No se encontraron préstamos') }}</h4>
                                        @role('admin|encargado')
                                        <p class="text-muted">{{ __('Puedes comenzar agregando un nuevo préstamo') }}</p>
                                        <a href="{{ route('prestamos.create') }}" class="btn btn-primary mt-2">
                                            <i class="fas fa-plus"></i> {{ __('Agregar Préstamo') }}
                                        </a>
                                        @endrole
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>

                </div>

                @if($prestamos->hasPages())
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <p class="text-muted">
                                Mostrando {{ $prestamos->firstItem() }} a {{ $prestamos->lastItem() }}
                                de {{ $prestamos->total() }} registros
                            </p>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-end">
                                {!! $prestamos->withQueryString()->links() !!}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal para devoluciones -->
<div class="modal fade" id="devolucionModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header text-white bg-primary">
                <h5 class="modal-title"><i class="fa fa-undo"></i> Registrar Devolución</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="devolucionForm" action="{{ route('devolucions.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="prestamo_id" id="prestamo_id">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Material:</label>
                                <input type="text" class="form-control" id="modal_material" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Cantidad Prestada:</label>
                                <input type="text" class="form-control" id="modal_cantidad" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Personal:</label>
                                <input type="text" class="form-control" id="modal_user" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Descripción:</label>
                                <input type="text" class="form-control" id="modal_descripcion" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="cantidad_devuelta">Cantidad a Devolver:</label>
                                <input type="number" name="cantidad_devuelta" id="cantidad_devuelta"
                                       class="form-control" required min="1">
                                <small class="form-text text-muted">No puede exceder la cantidad prestada</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="fecha_devolucion">Fecha de Devolución:</label>
                                <input type="date" name="fecha_devolucion" id="fecha_devolucion"
                                       class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="descripcion_estado">Estado del Material:</label>
                        <textarea name="descripcion_estado" id="descripcion_estado" class="form-control"
                                  rows="3" required placeholder="Describa el estado del material devuelto"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fa fa-times"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save"></i> Guardar Devolución
                    </button>
                </div>
            </form>
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
        <a id="go-to-index" href="#" class="dropdown-item text-success" style="display: none;">
            <i class="fas fa-edit mr-2"></i> Ir al índice
        </a>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>

<script>
    function confirmLogout(event) {
        event.preventDefault();
        if (confirm("¿Estás seguro de que deseas cerrar sesión?")) {
            document.getElementById('logout-form').submit();
        }
    }

    $(document).ready(function() {
        // Inicializar tooltips
        $('[data-toggle="tooltip"]').tooltip();

        const contextCells = document.querySelectorAll('.contextual');
        const contextMenu = document.getElementById('context-menu');
        const contextMenuTitle = document.getElementById('context-menu-title');
        const contextMenuContent = document.getElementById('context-menu-content');
        const filterSearch = document.getElementById('filter-search');
        const goToIndex = document.getElementById('go-to-index');

        const columnTitles = {
            'estado': 'Estado',
            'material': 'Material',
            'user': 'Personal'
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
                const url = this.getAttribute('data-url');

                const values = new Map();
                allRows.forEach(row => {
                    const cell = row.cells[column];
                    if (cell) {
                        const val = cell.innerText.trim();
                        if (val && !['Sin material', 'Sin personal'].includes(val)) {
                            values.set(val, val);
                        }
                    }
                });

                // Configurar título del menú
                contextMenuTitle.innerHTML = `<i class="fas fa-filter mr-1"></i> Filtrar por ${columnTitles[dataType]}`;

                // Mostrar u ocultar campo de búsqueda
                filterSearch.style.display = 'block';
                filterSearch.focus();

                // Mostrar u ocultar botón "Ir al índice"
                if (url) {
                    goToIndex.style.display = 'block';
                    goToIndex.setAttribute('href', url);
                } else {
                    goToIndex.style.display = 'none';
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

        // Cuando se abre el modal de devolución
        $('#devolucionModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var prestamoId = button.data('prestamo-id');
            var material = button.data('material');
            var cantidad = button.data('cantidad');
            var user = button.data('user');
            var descripcion = button.data('descripcion');

            var modal = $(this);
            modal.find('#prestamo_id').val(prestamoId);
            modal.find('#modal_material').val(material);
            modal.find('#modal_cantidad').val(cantidad);
            modal.find('#modal_user').val(user);
            modal.find('#modal_descripcion').val(descripcion);

            // Establecer el máximo para la cantidad a devolver
            $('#cantidad_devuelta').attr('max', cantidad);
        });

        // Validación del formulario antes de enviar
        $('#devolucionForm').submit(function(e) {
            const cantidadDevuelta = parseInt($('#cantidad_devuelta').val());
            const cantidadPrestada = parseInt($('#modal_cantidad').val());

            if (cantidadDevuelta > cantidadPrestada) {
                alert('La cantidad devuelta no puede ser mayor que la cantidad prestada');
                e.preventDefault();
                return false;
            }

            if (cantidadDevuelta <= 0) {
                alert('La cantidad devuelta debe ser mayor que cero');
                e.preventDefault();
                return false;
            }

            if ($('#descripcion_estado').val().trim() === '') {
                alert('Debe describir el estado del material devuelto');
                e.preventDefault();
                return false;
            }

            return true;
        });
    });
</script>

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>
</body>
</html>
