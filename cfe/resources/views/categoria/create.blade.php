@extends('layouts.app')

@section('template_title')
    {{ __('Crear Categoría') }}
@endsection

@section('content')
    <div class="container">
        <div class="card shadow-lg">
            <div class="card-header text-center text-white" style="background-color: #00723E;">
                <h3 class="mb-0"><i class="fa fa-plus-circle"></i> {{ __('Nueva Categoría') }}</h3>
            </div>

            <div class="card-body bg-white">
                <form method="POST" action="{{ route('categorias.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label for="nombre" class="form-label"><strong>Nombre de la Categoría:</strong></label>
                        <input type="text" name="nombre" id="nombre" class="form-control @error('nombre') is-invalid @enderror"
                               placeholder="Ej. Electrónica" value="{{ old('nombre') }}" required>
                        @error('nombre')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="descripcion" class="form-label"><strong>Descripción:</strong></label>
                        <textarea name="descripcion" id="descripcion" class="form-control @error('descripcion') is-invalid @enderror"
                                  placeholder="Ej. Productos electrónicos y accesorios" rows="3">{{ old('descripcion') }}</textarea>
                        @error('descripcion')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('categorias.index') }}" class="btn btn-warning">
                            <i class="fa fa-arrow-left"></i> Volver
                        </a>
                        <button type="submit" class="btn text-white" style="background-color: #4CAF50;">
                            <i class="fa fa-save"></i> Guardar Categoría
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
