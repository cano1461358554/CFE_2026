@extends('layouts.app')

@section('template_title')
    {{ __('Devoluciones') }}
@endsection

@section('content')
    <div class="container">
        <div class="card shadow-lg">
            <div class="card-header text-center text-white" style="background-color: #00723E;">
                <h3 class="mb-0"><i class="fa fa-undo"></i> {{ __('Gestión de Devoluciones') }}</h3>
            </div>

            <div class="card-body">
                @if ($message = Session::get('success'))
                    <div class="alert alert-success text-center">
                        <p>{{ $message }}</p>
                    </div>
                @endif

                <form method="GET" action="{{ route('devolucions.index') }}" class="mb-4">
                    <div class="row">
                        <div class="col-md-5">
                            <input type="text" name="descripcion_estado" class="form-control" placeholder="Buscar por estado..." value="{{ request('descripcion_estado') }}">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn text-white" style="background-color: #4CAF50;">
                                <i class="fa fa-search"></i> Buscar
                            </button>
                            <a href="{{ route('devolucions.index') }}" class="btn btn-warning">
                                <i class="fa fa-sync-alt"></i> Limpiar
                            </a>
                        </div>
                        <div class="col-md-4 text-end">
                            <a href="{{ route('devolucions.create') }}" class="btn text-white" style="background-color: #A4D65E;">
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

                @if(isset($prestamo))
                    <div class="info-box mb-4 p-3" style="background-color: white; border-left: 4px solid #00723E; border-radius: 5px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                        <h4 class="mb-3">Información del Préstamo #{{ $prestamo->id }}</h4>
                        <div class="row">
                            <div class="col-md-4">
                                <p><strong>Material:</strong> {{ $prestamo->material->nombre ?? 'No especificado' }}</p>
                            </div>
                            <div class="col-md-4">
                                <p><strong>Personal:</strong> {{ $prestamo->personal->nombre ?? 'No especificado' }} {{ $prestamo->personal->apellido ?? '' }}</p>
                            </div>
                            <div class="col-md-2">
                                <p><strong>Cantidad:</strong> {{ $prestamo->cantidad_prestada }}</p>
                            </div>
                            <div class="col-md-2">
                                <p><strong>Fecha:</strong> {{ $prestamo->fecha_prestamo }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="text-white text-center" style="background-color: #00723E;">
                        <tr>
{{--                            <th>No</th>--}}
                            <th>Fecha Devolución</th>
                            <th>Cantidad Devuelta</th>
                            <th>Estado</th>
                            <th>Observaciones</th>
                            @if(!isset($prestamo))
{{--                                <th>Material</th>--}}
{{--                                <th>Almacén</th>--}}
                            @endif
                            <th>Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php
                            $estadoBuscado = request('descripcion_estado');
                            $i = ($devolucions->currentPage() - 1) * $devolucions->perPage() + 1;
                        @endphp

                        @forelse ($devolucions as $devolucion)
                            @php
                                $coincide = $estadoBuscado && stripos($devolucion->descripcion_estado, $estadoBuscado) !== false;
                            @endphp
                            <tr class="{{ $coincide ? 'table-success' : '' }}">
{{--                                <td class="text-center">{{ $i++ }}</td>--}}
                                <td>{{ $devolucion->fecha_devolucion }}</td>
                                <td>{{ $devolucion->cantidad_devuelta }}</td>
                                <td>{{ $devolucion->descripcion_estado }}</td>
                                <td>{{ $devolucion->observaciones ?? 'Sin observaciones' }}</td>
                                @if(!isset($prestamo))
{{--                                    <td>{{ $devolucion->material->nombre ?? 'Sin material' }}</td>--}}
{{--                                    <td>{{ $devolucion->almacen->nombre ?? 'Sin almacén' }}</td>--}}
                                @endif
                                <td class="text-center">
                                    @if(!isset($prestamo))
                                        <a href="{{ route('devolucions.edit', $devolucion->id) }}" class="btn btn-warning btn-sm">
                                            <i class="fa fa-edit"></i> Editar
                                        </a>
                                    @endif
                                    <form action="{{ route('devolucions.destroy', $devolucion->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar devolución?')">
                                            <i class="fa fa-trash"></i> Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ isset($prestamo) ? 6 : 8 }}" class="text-center text-danger"><strong>No hay devoluciones registradas.</strong></td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-3">
                    {!! $devolucions->withQueryString()->links() !!}
                </div>

                @if(isset($prestamo))
                    <div class="text-center mt-4">
                        <a href="{{ route('prestamos.show', $prestamo->id) }}" class="btn btn-secondary">
                            <i class="fa fa-arrow-left"></i> Volver al Préstamo
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
