@extends('layouts.app')

@section('template_title')
    {{ __('Mostrar Almacén') }}
@endsection

@section('content')
    <div class="container">
        <div class="card shadow-lg">
            <div class="card-header text-center text-white" style="background-color: #00723E;">
                <h3 class="mb-0"><i class="fa fa-eye"></i> {{ __('Mostrar Almacén') }}</h3>
            </div>

            <div class="card-body bg-white">
                <div class="mb-3">
                    <label for="nombre" class="form-label"><strong>Nombre del Almacén:</strong></label>
                    <input type="text" name="nombre" id="nombre" class="form-control" value="{{ $almacen->nombre }}" readonly>
                </div>

                <div class="mb-3">
                    <label for="ubicacion" class="form-label"><strong>Ubicación:</strong></label>
                    <input type="text" name="ubicacion" id="ubicacion" class="form-control" value="{{ $almacen->ubicacion->ubicacion }}" readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label"><strong>Materiales en el Almacén:</strong></label>
                    @if ($almacen->materials->isEmpty())
                        <p>No hay materiales en este almacén.</p>
                    @else
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Nombre del Material</th>
                                <th>Cantidad</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($almacen->materials as $material)
                                <tr>
                                    <td>{{ $material->nombre }}</td>
                                    <td>{{ $material->pivot->cantidad }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('almacens.index') }}" class="btn btn-warning">
                        <i class="fa fa-arrow-left"></i> {{ __('Volver') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
