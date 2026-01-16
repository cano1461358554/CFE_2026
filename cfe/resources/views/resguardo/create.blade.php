@extends('layouts.app')

@section('template_title')
    {{ __('Crear Resguardo') }}
@endsection

@section('content')
    <div class="container">
        <div class="card shadow-lg">
            <div class="card-header text-center text-white" style="background-color: #00723E;">
                <h3 class="mb-0"><i class="fa fa-shield-alt"></i> {{ __('Crear Nuevo Resguardo') }}</h3>
            </div>

            <div class="card-body bg-white">
                <form method="POST" action="{{ route('resguardos.store') }}" role="form" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label for="fecha_resguardo">Fecha de Resguardo</label>
                        <input type="date" class="form-control" id="fecha_resguardo" name="fecha_resguardo"
                               value="{{ now()->toDateString() }}" readonly>
                        <small class="form-text text-muted">Generada automáticamente</small>
                    </div>

                    <div class="form-group mb-4">
                        <label for="estado">{{ __('Estado') }}</label>
                        <select name="estado" id="estado" class="form-control" required>
                            <option value="activo">Activo</option>
                            <option value="inactivo">Inactivo</option>
                        </select>
                    </div>

                    <div class="form-group mb-4">
                        <label for="personal_id">{{ __('Personal') }}</label>
                        <select name="personal_id" id="personal_id" class="form-control" required
                                onchange="cargarPrestamos(this.value)">
                            <option value="">Seleccione un personal</option>
                            @foreach ($personals as $personal)
                                <option value="{{ $personal->id }}">
                                    {{ $personal->nombre }} {{ $personal->apellido }} (RP: {{ $personal->RP }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-4">
                        <label for="prestamo_id">{{ __('Préstamo Relacionado') }}</label>
                        <select name="prestamo_id" id="prestamo_id" class="form-control" required disabled>
                            <option value="">Primero seleccione un personal</option>
                            @foreach ($prestamos as $prestamo)
                                <option value="{{ $prestamo->id }}">
                                    Préstamo #{{ $prestamo->id }} ({{ $prestamo->desc_uso }})
                                </option>
                            @endforeach
                        </select>
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

    @section('scripts')
        <script>
            function cargarPrestamos(personalId) {
                if (!personalId) {
                    $('#prestamo_id').html('<option value="">Primero seleccione un personal</option>');
                    $('#prestamo_id').prop('disabled', true);
                    return;
                }

                $.get('/prestamos-por-personal/' + personalId, function(data) {
                    $('#prestamo_id').html('<option value="">Seleccione un préstamo</option>');

                    $.each(data, function(key, prestamo) {
                        $('#prestamo_id').append(
                            `<option value="${prestamo.id}">
                            Préstamo #${prestamo.id} (${prestamo.desc_uso})
                        </option>`
                        );
                    });

                    $('#prestamo_id').prop('disabled', false);
                });
            }
        </script>
    @endsection
@endsection
