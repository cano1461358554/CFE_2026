@extends('layouts.app')
<!-- Al principio de tu vista -->

@section('template_title')
    {{ __('Control de Movimientos - CFE') }}
@endsection

@section('content')


    <div class="container-fluid">
        <!-- Header CFE -->
{{--        <div class="row mb-4">--}}
{{--            <div class="col-12">--}}
{{--                <div class="card border-0 shadow-lg">--}}
{{--                    <div class="card-body p-4">--}}
{{--                        <div class="row align-items-center">--}}
{{--                            <div class="col-md-8">--}}
{{--                                <h1 class="h2 text-primary mb-1">--}}
{{--                                    <strong>Sistema Unificado de Movimientos</strong>--}}
{{--                                </h1>--}}
{{--                                <p class="text-muted mb-0">--}}
{{--                                    <i class="fas fa-exchange-alt mr-1"></i> Gestión de Préstamos, Resguardos y Salidas--}}
{{--                                </p>--}}
{{--                                <p class="text-muted mb-0">--}}
{{--                                    <i class="fas fa-building mr-1"></i> CFE - Comisión Federal de Electricidad--}}
{{--                                </p>--}}
{{--                            </div>--}}
{{--                            <div class="col-md-4 text-right">--}}
{{--                                <div class="badge badge-primary p-2">--}}
{{--                                    <i class="fas fa-database mr-1"></i>--}}
{{--                                    Total: {{ $movimientos->total() }} registros--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}

        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-lg">
                    <div class="card-header bg-gradient-primary text-black py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h3 class="h4 mb-0">
                                    <i class="fas fa-exchange-alt mr-2"></i>
                                    {{ __('Control de Movimientos') }}
                                </h3>
                                <p class="mb-0 opacity-75">
                                    Sistema unificado para gestionar todos los tipos de movimientos
                                </p>
                                <div class="col-md-4 text-right">
                                    <div class="badge badge-primary p-2">
                                        <i class="fas fa-database mr-1"></i>
                                        Total: {{ $movimientos->total() }} registros
                                    </div>
                                </div>
                            </div>
                            @role('admin|encargado')
                            <div class="btn-group">
                                <a href="{{ route('movimientos.create') }}" class="btn btn-success">
                                    <i class="fas fa-plus-circle mr-1"></i> {{ __('Nuevo Movimiento') }}
                                </a>
                            </div>
                            @endrole
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

                        <!-- ===== MODIFICACIÓN: FILTROS EN ACORDEÓN ===== -->
                        <div class="card mb-4">
                            <div class="card-header bg-light py-2" id="filtrosHeading">
                                <h6 class="mb-0">
                                    <button class="btn btn-link text-dark font-weight-bold" type="button" data-toggle="collapse" data-target="#filtrosCollapse" aria-expanded="false" aria-controls="filtrosCollapse">
                                        <i class="fas fa-filter mr-2"></i> Filtros Avanzados
                                        <i class="fas fa-chevron-down float-right"></i>
                                    </button>
                                </h6>
                            </div>
                            <div id="filtrosCollapse" class="collapse" aria-labelledby="filtrosHeading">
                                <div class="card-body p-3">
                                    <!-- Filtros de tipos y estados -->
                                    <form method="GET" action="{{ route('movimientos.index') }}">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group mb-2">
                                                    <label for="tipo" class="small font-weight-bold">Tipo</label>
                                                    <select name="tipo" id="tipo" class="form-control form-control-sm">
                                                        <option value="">Todos</option>
                                                        <option value="Préstamo" {{ request('tipo') == 'Préstamo' ? 'selected' : '' }}>Préstamo</option>
                                                        <option value="Resguardo" {{ request('tipo') == 'Resguardo' ? 'selected' : '' }}>Resguardo</option>
                                                        <option value="Salida Sin Retorno" {{ request('tipo') == 'Salida Sin Retorno' ? 'selected' : '' }}>Salida Sin Retorno</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group mb-2">
                                                    <label for="estado" class="small font-weight-bold">Estado</label>
                                                    <select name="estado" id="estado" class="form-control form-control-sm">
                                                        <option value="">Todos</option>
                                                        <option value="activo" {{ request('estado') == 'activo' ? 'selected' : '' }}>Activo</option>
                                                        <option value="devuelto" {{ request('estado') == 'devuelto' ? 'selected' : '' }}>Devuelto</option>
                                                        <option value="perdido" {{ request('estado') == 'perdido' ? 'selected' : '' }}>Perdido</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group mb-2">
                                                    <label for="material_id" class="small font-weight-bold">Material</label>
                                                    <select name="material_id" id="material_id" class="form-control form-control-sm">
                                                        <option value="">Todos</option>
                                                        @foreach($materials as $material)
                                                            <option value="{{ $material->id }}"
                                                                {{ request('material_id') == $material->id ? 'selected' : '' }}>
                                                                {{ $material->nombre }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group mb-2">
                                                    <label for="fecha_inicio" class="small font-weight-bold">Fecha Inicio</label>
                                                    <input type="date" name="fecha_inicio" id="fecha_inicio"
                                                           class="form-control form-control-sm" value="{{ request('fecha_inicio') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group mb-2">
                                                    <label for="fecha_fin" class="small font-weight-bold">Fecha Fin</label>
                                                    <input type="date" name="fecha_fin" id="fecha_fin"
                                                           class="form-control form-control-sm" value="{{ request('fecha_fin') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-3 align-self-end">
                                                <div class="form-group mb-2">
                                                    <button type="submit" class="btn btn-primary btn-sm btn-block">
                                                        <i class="fas fa-filter mr-1"></i> Aplicar Filtros
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="col-md-3 align-self-end">
                                                <div class="form-group mb-2">
                                                    <a href="{{ route('movimientos.index') }}" class="btn btn-outline-secondary btn-sm btn-block">
                                                        <i class="fas fa-broom mr-1"></i> Limpiar Filtros
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Tabla de movimientos -->
                        <div class="table-responsive rounded">
                            <table class="table table-hover table-bordered">
                                <thead class="thead-light">
                                <tr>
                                    <th>{{ __('Fecha') }}</th>
                                    <th>{{ __('Cantidad') }}</th>
                                    <th class="contextual" data-type="tipo">{{ __('Tipo') }}</th>
                                    <th class="contextual" data-type="material">{{ __('Material') }}</th>
                                    <th class="contextual" data-type="usuario">{{ __('Usuario Asignado') }}</th>
                                    <th>{{ __('Estado') }}</th>
                                    <th>{{ __('Notas') }}</th>
                                    @role('admin|encargado')
                                    <th class="text-center" style="width: 180px;">{{ __('Acciones') }}</th>
                                    @endrole
                                </tr>
                                </thead>
                                <tbody>
                                @forelse ($movimientos as $movimiento)
                                    @php
                                        // ===== INTEGRACIÓN: CÁLCULO DE DEVOLUCIONES =====
                                        // Usar accesores del modelo que ya manejan null
                                        $cantidad_devuelta = $movimiento->cantidad_devuelta;
                                        $pendiente = $movimiento->cantidad_pendiente;
                                        $completamente_devuelto = $movimiento->completamente_devuelto;

                                        // Verificar relaciones primero
                                        $es_prestamo = $movimiento->tipoMovimiento &&
                                                      $movimiento->tipoMovimiento->descripcion == 'Préstamo';
                                        $es_resguardo = $movimiento->tipoMovimiento &&
                                                       $movimiento->tipoMovimiento->descripcion == 'Resguardo';

                                        // Resaltar si coincide con búsqueda
                                        $coincide = request('usuario') &&
                                            $movimiento->usuarioAsignado &&
                                            (stripos($movimiento->usuarioAsignado->name ?? '', request('usuario')) !== false ||
                                             stripos($movimiento->usuarioAsignado->email ?? '', request('usuario')) !== false);
                                    @endphp

                                    <tr class="{{ $completamente_devuelto ? 'table-success' : '' }}
                                               {{ $coincide ? 'table-info' : '' }}
                                               {{ $movimiento->estado == 'perdido' ? 'table-danger' : '' }}">
                                        <td>
                                            {{ $movimiento->fecha->format('d/m/Y') }}
                                            @if($movimiento->fecha_devolucion_estimada && $es_prestamo)
                                                <br>
                                                <small class="text-muted">
                                                    Dev est: {{ $movimiento->fecha_devolucion_estimada->format('d/m/Y') }}
                                                </small>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $movimiento->cantidad }}
                                            @if($es_prestamo || $es_resguardo)
                                                <br>
                                                <small class="text-muted">
                                                    Devuelto: {{ $cantidad_devuelta }} | Pendiente: {{ $pendiente }}
                                                </small>
                                            @endif
                                        </td>
                                        <td class="contextual" data-type="tipo">
                                            @if($movimiento->tipoMovimiento->descripcion == 'Resguardo')
                                                <span class="badge badge-info">
                                                    <i class="fas fa-shield-alt"></i> Resguardo
                                                </span>
                                            @elseif($movimiento->tipoMovimiento->descripcion == 'Préstamo')
                                                <span class="badge badge-warning">
                                                    <i class="fas fa-handshake"></i> Préstamo
                                                </span>
                                            @elseif($movimiento->tipoMovimiento->descripcion == 'Salida Sin Retorno')
                                                <span class="badge badge-danger">
                                                    <i class="fas fa-exclamation-triangle"></i> Sin Retorno
                                                </span>
                                            @else
                                                <span class="badge badge-secondary">
                                                    {{ $movimiento->tipoMovimiento->descripcion }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="contextual" data-type="material" data-url="{{ route('materials.index') }}">
                                            {{ $movimiento->material->nombre ?? 'Material eliminado' }}
                                            @if($movimiento->material && $movimiento->material->trashed())
                                                <span class="badge badge-warning">Eliminado</span>
                                            @endif
                                        </td>
                                        <td class="contextual" data-type="usuario" data-url="{{ route('users.index') }}">
                                            @if($movimiento->usuarioAsignado)

                                                <strong>{{ ($movimiento->usuarioAsignado->nombre ?? '') . ' ' . ($movimiento->usuarioAsignado->apellido ?? '') }}</strong>


                                                <br>
                                                <small class="text-muted">{{ $movimiento->usuarioAsignado->email ?? '' }}</small>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($movimiento->estado == 'activo')
                                                @if($completamente_devuelto)
                                                    <span class="badge badge-success">Completamente Devuelto</span>
                                                @elseif($pendiente > 0 && ($es_prestamo || $es_resguardo))
                                                    <span class="badge badge-warning">Pendiente ({{ $pendiente }})</span>
                                                @else
                                                    <span class="badge badge-primary">Activo</span>
                                                @endif
                                            @elseif($movimiento->estado == 'devuelto')
                                                <span class="badge badge-secondary">Devuelto</span>
                                            @elseif($movimiento->estado == 'perdido')
                                                <span class="badge badge-danger">Perdido</span>
                                            @endif

                                            @if($movimiento->fecha_devolucion_estimada && $movimiento->fecha_devolucion_estimada < now() && $movimiento->estado == 'activo')
                                                <br><small class="text-danger"><i class="fas fa-exclamation-circle"></i> Vencido</small>
                                            @endif
                                        </td>
                                        <td>{{ Str::limit($movimiento->notas, 50) }}</td>
                                        @role('admin|encargado')
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <!-- Ver -->
                                                <a class="btn btn-info btn-sm" href="{{ route('movimientos.show', $movimiento->id) }}"
                                                   title="Ver detalles">
                                                    <i class="fas fa-eye"></i>
                                                </a>

                                                <!-- Editar -->
                                                <a class="btn btn-warning btn-sm" href="{{ route('movimientos.edit', $movimiento->id) }}"
                                                   title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                <!-- ===== INTEGRACIÓN: BOTÓN DE DEVOLUCIÓN ===== -->
                                                @if(($es_prestamo || $es_resguardo) && $movimiento->estado == 'activo' && $pendiente > 0)
{{--                                                    <button class="btn btn-info btn-sm btn-devolucion"--}}
{{--                                                            data-toggle="modal"--}}
{{--                                                            data-target="#devolucionModal"--}}
{{--                                                            data-movimiento-id="{{ $movimiento->id }}"--}}
{{--                                                            data-material="{{ $movimiento->material->nombre ?? 'Sin material' }}"--}}
{{--                                                            data-cantidad="{{ $pendiente }}"--}}
{{--                                                            data-user="{{ $movimiento->usuarioAsignado->name ?? 'Sin usuario' }}"--}}
{{--                                                            data-descripcion="{{ $movimiento->notas }}"--}}
{{--                                                            title="Registrar devolución">--}}
{{--                                                        <i class="fas fa-undo"></i>--}}
{{--                                                    </button>--}}
                                                    <button class="btn btn-info btn-sm btn-devolucion"
                                                            data-toggle="modal"
                                                            data-target="#devolucionModal"
                                                            data-movimiento-id="{{ $movimiento->id }}"
                                                            data-material="{{ $movimiento->material->nombre ?? 'Sin material' }}"
                                                            data-cantidad="{{ $pendiente }}"
                                                            data-user="{{ $movimiento->usuarioAsignado->name ?? 'Sin usuario' }}"
                                                            data-descripcion="{{ $movimiento->notas }}">
                                                        <i class="fas fa-undo"></i>
                                                    </button>
                                                @endif

                                                <!-- Convertir a Resguardo (solo para préstamos activos) -->
{{--                                                @if($es_prestamo && $movimiento->estado == 'activo')--}}
{{--                                                    <form action="{{ route('movimientos.convertir-resguardo', $movimiento->id) }}"--}}
{{--                                                          method="POST" class="d-inline">--}}
{{--                                                        @csrf--}}
{{--                                                        <button type="submit" class="btn btn-primary btn-sm"--}}
{{--                                                                title="Convertir a Resguardo"--}}
{{--                                                                onclick="return confirm('¿Convertir este préstamo a resguardo?')">--}}
{{--                                                            <i class="fas fa-shield-alt"></i>--}}
{{--                                                        </button>--}}
{{--                                                    </form>--}}
{{--                                                @endif--}}
                                                @if($es_prestamo && $movimiento->estado == 'activo')
                                                    <form action="{{ route('movimientos.convertir-resguardo', $movimiento->id) }}"
                                                          method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-primary btn-sm"
                                                                title="Convertir a Resguardo"
                                                                onclick="return confirm('¿Convertir este préstamo a resguardo?')">
                                                            <i class="fas fa-shield-alt"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                                <!-- Eliminar -->
                                                <form action="{{ route('movimientos.destroy', $movimiento->id) }}"
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm"
                                                            title="Eliminar"
                                                            onclick="return confirm('¿Está seguro de eliminar este movimiento?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                        @endrole
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="@role('admin|encargado')8 @else 7 @endrole" class="text-center py-4">
                                            <div class="empty-state">
                                                <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                                                <h4 class="text-muted">{{ __('No se encontraron movimientos') }}</h4>
                                                @role('admin|encargado')
                                                <p class="text-muted">{{ __('Puedes comenzar agregando un nuevo movimiento') }}</p>
                                                <a href="{{ route('movimientos.create') }}" class="btn btn-primary mt-2">
                                                    <i class="fas fa-plus"></i> {{ __('Agregar Movimiento') }}
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
                        @if($movimientos->hasPages())
                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <p class="text-muted">
                                        Mostrando {{ $movimientos->firstItem() }} a {{ $movimientos->lastItem() }}
                                        de {{ $movimientos->total() }} registros
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex justify-content-end">
                                        {!! $movimientos->appends(request()->query())->links() !!}
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Resumen de movimientos -->
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0"><i class="fas fa-chart-bar"></i> Resumen de Movimientos</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row text-center">
                                            <div class="col-md-3">
                                                <div class="card bg-primary text-white">
                                                    <div class="card-body">
                                                        <h5>Préstamos Activos</h5>
                                                        <h3>{{ $movimientos->where('tipoMovimiento.descripcion', 'Préstamo')->where('estado', 'activo')->count() }}</h3>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="card bg-info text-white">
                                                    <div class="card-body">
                                                        <h5>Resguardos Activos</h5>
                                                        <h3>{{ $movimientos->where('tipoMovimiento.descripcion', 'Resguardo')->where('estado', 'activo')->count() }}</h3>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="card bg-danger text-white">
                                                    <div class="card-body">
                                                        <h5>Salidas Sin Retorno</h5>
                                                        <h3>{{ $movimientos->where('tipoMovimiento.descripcion', 'Salida Sin Retorno')->count() }}</h3>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="card bg-success text-white">
                                                    <div class="card-body">
                                                        <h5>Total Devueltos</h5>
                                                        <h3>{{ $movimientos->where('estado', 'devuelto')->count() }}</h3>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== INTEGRACIÓN: MODAL DE DEVOLUCIONES ===== -->
    <!-- ===== MODAL ÚNICO DE DEVOLUCIONES ===== -->
    <div class="modal fade" id="devolucionModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header text-white bg-primary">
                    <h5 class="modal-title"><i class="fas fa-undo"></i> Registrar Devolución</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- FORMULARIO CORREGIDO -->
                <form id="devolucionForm">
                    @csrf
                    <div class="modal-body">
                        <!-- Campo oculto con el ID del movimiento -->
                        <input type="hidden" id="movimiento_id_hidden">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Material:</label>
                                    <input type="text" class="form-control" id="modal_material" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Cantidad Pendiente:</label>
                                    <input type="text" class="form-control" id="modal_cantidad" readonly>
                                </div>
                            </div>
                        </div>

                        <!-- AGREGAR ESTA SECCIÓN QUE FALTA -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Usuario Asignado:</label>
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
                                    <label for="cantidad_devuelta">Cantidad a Devolver *:</label>
                                    <input type="number" name="cantidad_devuelta" id="cantidad_devuelta"
                                           class="form-control" required min="0.01" step="0.01"
                                           placeholder="Ingrese cantidad a devolver">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fecha_devolucion">Fecha de Devolución *:</label>
                                    <input type="date" name="fecha_devolucion" id="fecha_devolucion"
                                           class="form-control" value="{{ date('Y-m-d') }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="almacen_id">Almacén de Devolución *:</label>
                                    <select name="almacen_id" id="almacen_id" class="form-control" required>
                                        <option value="">Seleccione almacén...</option>
                                        @foreach(\App\Models\Almacen::all() as $almacen)
                                            <option value="{{ $almacen->id }}">{{ $almacen->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="descripcion_estado">Estado del Material *:</label>
                            <textarea name="descripcion_estado" id="descripcion_estado" class="form-control"
                                      rows="3" required placeholder="Describa el estado del material"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times"></i> Cancelar
                        </button>
                        <button type="button" class="btn btn-primary" id="btnGuardarDevolucion">
                            <i class="fas fa-save"></i> Guardar Devolución
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Asegúrate que esto esté en tu layout o antes del script -->
{{--    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>--}}
{{--    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>--}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>
    <!-- Luego tu modal con el script -->
    <!-- JavaScript CORREGIDO - Colócalo AL FINAL de tu vista, antes de </body> -->
    <script>
        $(document).ready(function() {
            console.log('Document ready - JavaScript cargado');

            var movimientoIdActual = 0;
            var maxCantidad = 0;

            // Cuando se abre el modal
            $('#devolucionModal').on('show.bs.modal', function (event) {
                console.log('Modal abierto');
                var button = $(event.relatedTarget);
                movimientoIdActual = button.data('movimiento-id');
                var material = button.data('material');
                var cantidad = button.data('cantidad');
                var user = button.data('user');
                var descripcion = button.data('descripcion');

                console.log('Datos del botón:', {
                    movimientoId: movimientoIdActual,
                    material: material,
                    cantidad: cantidad,
                    user: user,
                    descripcion: descripcion
                });

                // Llenar campos
                $('#modal_material').val(material);
                $('#modal_cantidad').val(cantidad);
                $('#modal_user').val(user);
                $('#modal_descripcion').val(descripcion);
                $('#movimiento_id_hidden').val(movimientoIdActual);

                // Configurar cantidad máxima
                maxCantidad = parseFloat(cantidad);
                $('#cantidad_devuelta').attr('max', maxCantidad);
                $('#cantidad_devuelta').val('');

                console.log('Modal configurado, maxCantidad:', maxCantidad);
            });

            // Botón para guardar devolución
            $('#btnGuardarDevolucion').click(function() {
                console.log('=== CLICK EN BOTÓN GUARDAR ===');
                console.log('movimientoIdActual:', movimientoIdActual);

                var cantidadDevuelta = parseFloat($('#cantidad_devuelta').val()) || 0;
                var fechaDevolucion = $('#fecha_devolucion').val();
                var almacenId = $('#almacen_id').val();
                var descripcionEstado = $('#descripcion_estado').val().trim();

                console.log('Valores del formulario:', {
                    cantidadDevuelta: cantidadDevuelta,
                    fechaDevolucion: fechaDevolucion,
                    almacenId: almacenId,
                    descripcionEstado: descripcionEstado
                });

                // Validaciones
                if (cantidadDevuelta <= 0) {
                    console.log('Error: cantidad <= 0');
                    alert('La cantidad a devolver debe ser mayor a 0');
                    return;
                }

                if (cantidadDevuelta > maxCantidad) {
                    console.log('Error: excede maxCantidad', maxCantidad);
                    alert('La cantidad no puede exceder ' + maxCantidad);
                    return;
                }

                if (!fechaDevolucion) {
                    console.log('Error: falta fecha');
                    alert('La fecha de devolución es requerida');
                    return;
                }

                if (!almacenId) {
                    console.log('Error: falta almacén');
                    alert('Debe seleccionar un almacén');
                    return;
                }

                if (!descripcionEstado) {
                    console.log('Error: falta descripción');
                    alert('Debe describir el estado del material');
                    return;
                }

                // Confirmar
                if (!confirm('¿Registrar devolución de ' + cantidadDevuelta + ' unidades?')) {
                    console.log('Usuario canceló');
                    return;
                }

                console.log('Enviando AJAX...');

                // Mostrar loading
                $(this).prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Procesando...');

                // Enviar con AJAX
                $.ajax({
                    url: "{{ route('movimientos.procesar-devolucion', ':id') }}".replace(':id', movimientoIdActual),
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        cantidad_devuelta: cantidadDevuelta,
                        fecha_devolucion: fechaDevolucion,
                        almacen_id: almacenId,
                        descripcion_estado: descripcionEstado
                    },
                    success: function(response) {
                        console.log('Respuesta exitosa:', response);
                        if (response.success) {
                            alert(response.message);
                            $('#devolucionModal').modal('hide');
                            window.location.reload();
                        } else {
                            alert('Error: ' + response.message);
                            $('#btnGuardarDevolucion').prop('disabled', false).html('<i class="fas fa-save"></i> Guardar Devolución');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log('Error AJAX:', {
                            status: status,
                            error: error,
                            responseText: xhr.responseText,
                            statusText: xhr.statusText
                        });

                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            alert('Error: ' + xhr.responseJSON.message);
                        } else {
                            alert('Error en el servidor. Status: ' + xhr.status);
                        }
                        $('#btnGuardarDevolucion').prop('disabled', false).html('<i class="fas fa-save"></i> Guardar Devolución');
                    }
                });
            });

            console.log('Eventos configurados correctamente');
        });
    </script>

    @endsection


