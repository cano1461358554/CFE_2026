@extends('layouts.app')

@section('template_title')
    {{ __('Crear Tipo de Material') }}
@endsection

@section('content')
    <div class="container">
        <div class="card shadow-lg">
            <div class="card-header text-center text-white" style="background-color: #00723E;">
                <h3 class="mb-0"><i class="fa fa-plus-circle"></i> {{ __('Nuevo Tipo de Material') }}</h3>
            </div>

            <div class="card-body bg-white">
                <form method="POST" action="{{ route('tipo-materials.store') }}" enctype="multipart/form-data">
                    @csrf

                    <!-- Campo: Descripción del Tipo de Material -->
                    <div class="mb-3">
                        <label for="descripcion" class="form-label"><strong>Descripción del Tipo de Material:</strong></label>
                        <input type="text" name="descripcion" id="descripcion" class="form-control @error('descripcion') is-invalid @enderror"
                               placeholder="Ej. Plástico, Metal, Vidrio, etc." value="{{ old('descripcion') }}" required>
                        @error('descripcion')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Botones de Acción -->
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('tipo-materials.index') }}" class="btn btn-warning">
                            <i class="fa fa-arrow-left"></i> Volver
                        </a>
                        <button type="submit" class="btn text-white" style="background-color: #4CAF50;">
                            <i class="fa fa-save"></i> Guardar Tipo de Material
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
