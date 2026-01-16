@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Importar Usuarios desde CSV</h2>

        <form action="{{ route('users.import.process') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label for="csv_file" class="form-label">Archivo CSV</label>
                <input type="file" name="csv_file" id="csv_file" class="form-control" required>
            </div>

            <div class="form-check mb-3">
                <input type="checkbox" name="has_header" id="has_header" class="form-check-input" checked>
                <label for="has_header" class="form-check-label">El archivo contiene encabezado</label>
            </div>

            <button type="submit" class="btn btn-primary">Importar</button>

            <a href="{{ route('users.import.template') }}" class="btn btn-outline-secondary ms-2">
                Descargar plantilla CSV
            </a>
        </form>
    </div>
@endsection
