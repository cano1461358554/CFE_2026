@extends('layouts.app')

@section('template_title')
    {{ __('Editar Préstamo') }}
@endsection

@section('content')
    <div class="container">
        <div class="card shadow-lg">
            <div class="card-header text-center text-white" style="background-color: #00723E;">
                <h3 class="mb-0"><i class="fa fa-hand-holding-usd"></i> {{ __('Editar Préstamo') }}</h3>
            </div>

            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('prestamos.update', $prestamo->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="fecha_prestamo">{{ __('Fecha de Préstamo') }}</label>
                        <input type="date" name="fecha_prestamo" id="fecha_prestamo" class="form-control" value="{{ $prestamo->fecha_prestamo }}" required>
                    </div>

                    <div class="form-group">
                        <label for="cantidad_prestada">{{ __('Cantidad Prestada') }}</label>
                        <input type="number" name="cantidad_prestada" id="cantidad_prestada" class="form-control" value="{{ $prestamo->cantidad_prestada }}" required>
                    </div>

                    <div class="form-group">
                        <label for="material_id">{{ __('Material') }}</label>
                        <select name="material_id" class="form-control">
                            @foreach ($materials as $material)
                                <option value="{{ $material->id }}" {{ $prestamo->material_id == $material->id ? 'selected' : '' }}>{{ $material->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="almacen_id">{{ __('Almacén') }}</label>
                        <select name="almacen_id" id="almacen_id" class="form-control" required>
                            @foreach ($almacens as $almacen)
                                <option value="{{ $almacen->id }}" {{ $prestamo->almacen_id == $almacen->id ? 'selected' : '' }}>{{ $almacen->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="user_id">{{ __('Personal') }}</label>
                        <select name="user_id" id="user_id" class="form-control" required>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" {{ $prestamo->personal_id == $user->id ? 'selected' : '' }}>
                                    {{ $user->nombre }} {{ $user->apellido }} (RP: {{ $user->RP }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="descripcion">{{ __('Descripción de uso') }}</label>
                        <textarea name="descripcion" id="descripcion" class="form-control" required>{{ $prestamo->descripcion }}</textarea>
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
