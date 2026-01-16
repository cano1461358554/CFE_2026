@extends('layouts.app')

@section('template_title')
    {{ __('Editar Resguardo') }}
@endsection

@section('content')
    <div class="container">
        <div class="card shadow-lg">
            <div class="card-header text-center text-white" style="background-color: #00723E;">
                <h3 class="mb-0"><i class="fa fa-shield-alt"></i> {{ __('Editar Resguardo') }}</h3>
            </div>

            <div class="card-body bg-white">
                <form method="POST" action="{{ route('resguardos.update', $resguardo->id) }}" role="form" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="form-group mb-4">
                        <label for="fecha_resguardo">{{ __('Fecha de Resguardo') }}</label>
                        <input type="date" name="fecha_resguardo" id="fecha_resguardo" class="form-control" value="{{ $resguardo->fecha_resguardo }}" required>
                    </div>

                    <div class="form-group mb-4">
                        <label for="estado">{{ __('Estado') }}</label>
                        <select name="estado" id="estado" class="form-control" required>
                            <option value="activo" {{ $resguardo->estado == 'activo' ? 'selected' : '' }}>Activo</option>
                            <option value="inactivo" {{ $resguardo->estado == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                        </select>
                    </div>

                    <div class="form-group mb-4">
                        <label for="prestamo_id">{{ __('Préstamo Relacionado') }}</label>
                        <select name="prestamo_id" id="prestamo_id" class="form-control" required>
                            @foreach ($prestamos as $prestamo)
                                <option value="{{ $prestamo->id }}" {{ $resguardo->prestamo_id == $prestamo->id ? 'selected' : '' }}>
                                    Préstamo #{{ $prestamo->id }} ({{ $prestamo->desc_uso }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-4">
                        <label for="material_id">{{ __('Material') }}</label>
                        <select name="material_id" id="material_id" class="form-control">
                            @foreach ($materials as $material)
                                <option value="{{ $material->id }}" {{ $resguardo->material_id == $material->id ? 'selected' : '' }}>{{ $material->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-4">
                        <label for="almacen_id">{{ __('Almacén') }}</label>
                        <select name="almacen_id" id="almacen_id" class="form-control">
                            @foreach ($almacens as $almacen)
                                <option value="{{ $almacen->id }}" {{ $resguardo->almacen_id == $almacen->id ? 'selected' : '' }}>{{ $almacen->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-4">
                        <label for="personal_id">{{ __('Personal') }}</label>
                        <select name="personal_id" id="personal_id" class="form-control" required>
                            @foreach ($personals as $personal)
                                <option value="{{ $personal->id }}" {{ $resguardo->personal_id == $personal->id ? 'selected' : '' }}>
                                    {{ $personal->nombre }} {{ $personal->apellido }} (RP: {{ $personal->RP }})
                                </option>
                            @endforeach
                        </select>
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
