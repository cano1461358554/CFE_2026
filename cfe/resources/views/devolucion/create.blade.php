@extends('layouts.app')

@section('template_title')
    {{ __('Crear Devolución') }}
@endsection

@section('content')
    <div class="container">
        <div class="card shadow-lg">
            <div class="card-header text-center text-white" style="background-color: #00723E;">
                <h3 class="mb-0"><i class="fa fa-undo"></i> {{ __('Crear Nueva Devolución') }}</h3>
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route('devolucions.store') }}">
                    @csrf

                    <div class="form-group">
                        <label for="prestamo_id">{{ __('Préstamo') }}</label>
                        <select name="prestamo_id" id="prestamo_id" class="form-control" required>
                            <option value="">Seleccione un préstamo</option>
                            @foreach ($prestamos as $prestamo)
                                <option value="{{ $prestamo->id }}">
                                    Préstamo #{{ $prestamo->id }} - {{ $prestamo->desc_uso }} ({{ $prestamo->fecha_prestamo }})
                                </option>
                            @endforeach
                        </select>
                    </div>

{{--                    <!-- Campo para seleccionar material -->--}}
{{--                    <div class="form-group">--}}
{{--                        <label for="material_id">{{ __('Material') }}</label>--}}
{{--                        <select name="material_id" id="material_id" class="form-control" required>--}}
{{--                            <option value="">Seleccione un material</option>--}}
{{--                            @foreach ($materials as $material)--}}
{{--                                <option value="{{ $material->id }}">{{ $material->nombre }}</option>--}}
{{--                            @endforeach--}}
{{--                        </select>--}}
{{--                    </div>--}}

{{--                    <!-- Campo para seleccionar almacén -->--}}
{{--                    <div class="form-group">--}}
{{--                        <label for="almacen_id">{{ __('Almacén') }}</label>--}}
{{--                        <select name="almacen_id" id="almacen_id" class="form-control" required>--}}
{{--                            <option value="">Seleccione un almacén</option>--}}
{{--                            @foreach ($almacens as $almacen)--}}
{{--                                <option value="{{ $almacen->id }}">{{ $almacen->nombre }}</option>--}}
{{--                            @endforeach--}}
{{--                        </select>--}}
{{--                    </div>--}}

                    <!-- Campo para la fecha de devolución -->
{{--                    <div class="form-group">--}}
{{--                        <label for="fecha_devolucion">{{ __('Fecha de Devolución') }}</label>--}}
{{--                        <input type="date" name="fecha_devolucion" id="fecha_devolucion" class="form-control" required>--}}
{{--                    </div>--}}
                    <div class="form-group">
                        <label for="fecha_devolucion">Fecha de Devolución</label>
                        <input type="date" class="form-control" id="fecha_devolucion" name="fecha_devolucion"
                               value="{{ now()->toDateString() }}" readonly>
                        <small class="form-text text-muted">Generada automáticamente</small>
                    </div>

                    <div class="form-group">
                        <label for="cantidad_devuelta">{{ __('Cantidad Devuelta hola') }}</label>
                        <input type="number" name="cantidad_devuelta" id="cantidad_devuelta" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="descripcion_estado">{{ __('Descripción del Estado') }}</label>
                        <input type="text" name="descripcion_estado" id="descripcion_estado" class="form-control" required>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn text-white" style="background-color: #4CAF50;">
                            <i class="fa fa-save"></i> {{ __('Guardar') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
