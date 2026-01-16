@extends('layouts.app')

@section('template_title')
    {{ __('Crear Nuevo Almacén') }}
@endsection

@section('content')
    <div class="container">
        <div class="card shadow-lg">
            <div class="card-header text-center text-white" style="background-color: #00723E;">
                <h3 class="mb-0"><i class="fa fa-warehouse"></i> {{ __('Nuevo Almacén') }}</h3>
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route('almacens.store') }}">
                    @csrf

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="nombre" class="form-label">Nombre del Almacén *</label>
                            <input type="text" class="form-control @error('nombre') is-invalid @enderror"
                                   id="nombre" name="nombre" value="{{ old('nombre') }}"
                                   placeholder="Ej: Almacén de TI" required>
                            @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="ubicacion_id" class="form-label">Ubicación *</label>
                            <select class="form-control @error('ubicacion_id') is-invalid @enderror"
                                    id="ubicacion_id" name="ubicacion_id" required>
                                <option value="">Seleccione una ubicación</option>
                                @foreach($ubicacions as $ubicacion)
                                    <option value="{{ $ubicacion->id }}"
                                        {{ old('ubicacion_id') == $ubicacion->id ? 'selected' : '' }}>
                                        {{ $ubicacion->ubicacion }}
                                        @if($ubicacion->protegida)
                                            (Sistema)
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('ubicacion_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('almacens.index') }}" class="btn btn-secondary me-md-2">
                            <i class="fa fa-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn text-white" style="background-color: #4CAF50;">
                            <i class="fa fa-save"></i> Guardar Almacén
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
