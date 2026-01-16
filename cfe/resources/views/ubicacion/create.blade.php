@extends('layouts.app')

@section('template_title')
    {{ __('Crear Ubicación') }}
@endsection

@section('content')
    <div class="container">
        <div class="card shadow-lg">
            <div class="card-header text-center text-white" style="background-color: #00723E;">
                <h3 class="mb-0"><i class="fa fa-map-marker-alt"></i> {{ __('Nueva Ubicación') }}</h3>
            </div>

            <div class="card-body bg-white">
                <form method="POST" action="{{ route('ubicacions.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label for="ubicacion" class="form-label"><strong>Nombre de la Ubicación:</strong></label>
                        <input type="text" name="ubicacion" id="ubicacion" class="form-control @error('ubicacion') is-invalid @enderror"
                               placeholder="Ej. Almacén Central" value="{{ old('ubicacion') }}" required>
                        @error('ubicacion')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('ubicacions.index') }}" class="btn btn-warning">
                            <i class="fa fa-arrow-left"></i> Volver
                        </a>
                        <button type="submit" class="btn text-white" style="background-color: #4CAF50;">
                            <i class="fa fa-save"></i> Guardar Ubicación
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
