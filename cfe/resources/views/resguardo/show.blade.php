@extends('layouts.app')

@section('template_title')
    {{ __('Detalles del Resguardo') }}
@endsection

@section('content')
    <div class="container">
        <div class="card shadow-lg">
            <div class="card-header text-center text-white" style="background-color: #00723E;">
                <h3 class="mb-0"><i class="fa fa-shield-alt"></i> {{ __('Detalles del Resguardo') }}</h3>
            </div>

            <div class="card-body bg-white">
                <div class="text-end mb-4">
                    <a href="{{ route('resguardos.index') }}" class="btn text-white" style="background-color: #4CAF50;">
                        <i class="fa fa-arrow-left"></i> {{ __('Volver') }}
                    </a>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label class="fw-bold">{{ __('Fecha de Resguardo') }}</label>
                            <p class="form-control-static">{{ $resguardo->fecha_resguardo }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label class="fw-bold">{{ __('Estado') }}</label>
                            <p class="form-control-static">{{ $resguardo->estado }}</p>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <a href="{{ route('resguardos.edit', $resguardo->id) }}" class="btn text-white me-2" style="background-color: #FFC107;">
                        <i class="fa fa-edit"></i> {{ __('Editar') }}
                    </a>
                    <form action="{{ route('resguardos.destroy', $resguardo->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn text-white" style="background-color: #DC3545;" onclick="return confirm('¿Estás seguro de eliminar este resguardo?')">
                            <i class="fa fa-trash"></i> {{ __('Eliminar') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
