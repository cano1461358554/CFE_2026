@extends('layouts.app')

@section('template_title')
    {{ $prestamo->name ?? __('Ver') . " " . __('Préstamo') }}
@endsection

@section('content')
    <div class="container">
        <div class="card shadow-lg">
            <div class="card-header text-center text-white" style="background-color: #00723E;">
                <h3 class="mb-0"><i class="fa fa-hand-holding-usd"></i> {{ __('Detalles del Préstamo') }}</h3>
            </div>

            <div class="card-body bg-white">
                <div class="text-end mb-4">
                    <a href="{{ route('prestamos.index') }}" class="btn text-white" style="background-color: #4CAF50;">
                        <i class="fa fa-arrow-left"></i> {{ __('Volver') }}
                    </a>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label class="fw-bold">{{ __('Fecha de Préstamo') }}</label>
                            <p class="form-control-static">{{ $prestamo->fecha_prestamo }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label class="fw-bold">{{ __('Descripción de Uso') }}</label>
                            <p class="form-control-static">{{ $prestamo->descripcion }}</p>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <a href="{{ route('prestamos.edit', $prestamo->id) }}" class="btn text-white me-2" style="background-color: #FFC107;">
                        <i class="fa fa-edit"></i> {{ __('Editar') }}
                    </a>
                    <form action="{{ route('prestamos.destroy', $prestamo->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn text-white" style="background-color: #DC3545;" onclick="return confirm('¿Estás seguro de eliminar este préstamo?')">
                            <i class="fa fa-trash"></i> {{ __('Eliminar') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
