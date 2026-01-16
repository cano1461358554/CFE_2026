@extends('layouts.app')

@section('template_title')
    {{ $material->nombre ?? __('Detalles del Material') }}
@endsection

@section('content')
    <div class="container">
        <div class="card shadow-lg">
            <div class="card-header text-center text-white" style="background-color: #00723E;">
                <h3 class="mb-0"><i class="fa fa-box"></i> {{ __('Detalles del Material') }}</h3>
            </div>

            <div class="card-body bg-white">
                <div class="form-group mb-4">
                    <label for="nombre" class="form-label"><strong>{{ __('Nombre del Material') }}</strong></label>
                    <p>{{ $material->nombre }}</p>
                </div>

                <div class="form-group mb-4">
                    <label for="categoria_id" class="form-label"><strong>{{ __('Categoría') }}</strong></label>
                    <p>{{ $material->categoria->nombre ?? 'Sin categoría' }}</p>
                </div>

                <div class="form-group mb-4">
                    <label for="tipomaterial_id" class="form-label"><strong>{{ __('Tipo de Material') }}</strong></label>
                    <p>{{ $material->tipomaterial->descripcion ?? 'Sin tipo de material' }}</p>
                </div>

                <div class="form-group mb-4">
                    <label for="unidadmedida_id" class="form-label"><strong>{{ __('Unidad de Medida') }}</strong></label>
                    <p>{{ $material->unidadmedida->descripcion_unidad ?? 'Sin unidad de medida' }}</p>
                </div>

                <div class="text-center">
                    <a href="{{ route('materials.index') }}" class="btn text-white" style="background-color: #4CAF50;">
                        <i class="fa fa-arrow-left"></i> {{ __('Regresar') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
