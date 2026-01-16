@extends('layouts.app')

@section('template_title')
    {{ __('Crear Unidad de Medida') }}
@endsection

@section('content')
    <div class="container">
        <div class="card shadow-lg">
            <div class="card-header text-center text-white" style="background-color: #00723E;">
                <h3 class="mb-0"><i class="fa fa-plus-circle"></i> {{ __('Nueva Unidad de Medida') }}</h3>
            </div>

            <div class="card-body bg-white">
                <form method="POST" action="{{ route('unidad-medidas.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label for="descripcion_unidad" class="form-label"><strong>Descripción de la Unidad de Medida:</strong></label>
                        <input type="text" name="descripcion_unidad" id="descripcion_unidad" class="form-control @error('descripcion_unidad') is-invalid @enderror"
                               placeholder="Ej. Kilogramos, Litros, etc." value="{{ old('descripcion_unidad') }}" required>
                        @error('descripcion_unidad')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('unidad-medidas.index') }}" class="btn btn-warning">
                            <i class="fa fa-arrow-left"></i> Volver
                        </a>
                        <button type="submit" class="btn text-white" style="background-color: #4CAF50;">
                            <i class="fa fa-save"></i> Guardar Unidad de Medida
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
