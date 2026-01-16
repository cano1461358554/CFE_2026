@extends('layouts.app')

@section('template_title')
    {{ __('Resguardos') }}
@endsection

@section('content')
    <div class="container">
        <div class="card shadow-lg">
            <div class="card-header text-center text-white" style="background-color: #00723E;">
                <h3 class="mb-0"><i class="fa fa-shield-alt"></i> {{ __('Gestión de Resguardos') }}</h3>
            </div>

            <div class="card-body">
                @if ($message = Session::get('success'))
                    <div class="alert alert-success text-center">
                        <p>{{ $message }}</p>
                    </div>
                @endif

                <form method="GET" action="{{ route('resguardos.index') }}" class="mb-4">
                    <div class="row">
                        <div class="col-md-5">
                            <input type="date" name="fecha_resguardo" class="form-control" placeholder="Buscar por fecha de resguardo..." value="{{ request('fecha_resguardo') }}">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn text-white" style="background-color: #4CAF50;">
                                <i class="fa fa-search"></i> Buscar
                            </button>
                            <a href="{{ route('resguardos.index') }}" class="btn btn-warning">
                                <i class="fa fa-sync-alt"></i> Limpiar
                            </a>
                        </div>
                        <div class="col-md-4 text-end">
                            <a href="{{ route('resguardos.create') }}" class="btn text-white" style="background-color: #A4D65E;">
                                <i class="fa fa-plus-circle"></i> Crear Nuevo
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

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="text-white text-center" style="background-color: #00723E;">
                        <tr>
{{--                            <th>No</th>--}}
                            <th>Fecha de Resguardo</th>
                            <th>Estado</th>
                            <th>Préstamo Relacionado</th>
{{--                            <th>Material</th>--}}
{{--                            <th>Almacén</th>--}}
{{--                            <th>Personal</th> <!-- Nueva columna para el personal -->--}}
                            <th>Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php
                            $fechaBuscada = request('fecha_resguardo');
                            $i = ($resguardos->currentPage() - 1) * $resguardos->perPage() + 1; // Inicializar $i
                        @endphp

                        @forelse ($resguardos as $resguardo)
                            @php
                                $coincide = $fechaBuscada && $resguardo->fecha_resguardo == $fechaBuscada;
                            @endphp
                            <tr class="{{ $coincide ? 'table-success' : '' }}">
{{--                                <td class="text-center">{{ $i++ }}</td> <!-- Incrementar $i -->--}}
                                <td>{{ $resguardo->fecha_resguardo }}</td>
                                <td>{{ ucfirst($resguardo->estado) }}</td>
                                <td>
                                    @if ($resguardo->prestamo)
                                        Préstamo #{{ $resguardo->prestamo->id }} ({{ $resguardo->prestamo->desc_uso }})
                                    @else
                                        Sin préstamo asociado
                                    @endif
{{--                                </td>--}}
{{--                                <td>{{ $resguardo->material->nombre ?? 'Sin material' }}</td> <!-- Mostrar el nombre del material -->--}}
{{--                                <td>{{ $resguardo->almacen->nombre ?? 'Sin almacén' }}</td> <!-- Mostrar el nombre del almacén -->--}}
{{--                                <td>--}}


                                </td>
                                <td class="text-center">
                                    <a href="{{ route('resguardos.edit', $resguardo->id) }}" class="btn btn-warning btn-sm">
                                        <i class="fa fa-edit"></i> Editar
                                    </a>
                                    <form action="{{ route('resguardos.destroy', $resguardo->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar resguardo?')">
                                            <i class="fa fa-trash"></i> Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-danger"><strong>No hay resguardos registrados.</strong></td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-3">
                    {!! $resguardos->withQueryString()->links() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
