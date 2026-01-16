@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Importar Materiales desde CSV</h4>
            </div>

            <div class="card-body">

                {{-- Mensajes de éxito y error --}}
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('warning'))
                    <div class="alert alert-warning">
                        {{ session('warning') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                {{-- Mostrar errores de validación --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Errores encontrados:</strong>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Formulario de importación --}}
                <form action="{{ route('materials.import.process') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label for="csv_file" class="form-label">Archivo CSV</label>
                        <input type="file" name="csv_file" id="csv_file" class="form-control" accept=".csv" required>
                    </div>

                    <div class="form-check mb-3">
                        <input type="checkbox" name="has_header" id="has_header" class="form-check-input" checked>
                        <label for="has_header" class="form-check-label">El archivo contiene encabezado</label>
                    </div>

                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-upload"></i> Importar
                    </button>
                </form>

                <hr>

                <a href="{{ route('materials.import.template') }}" class="btn btn-outline-secondary mt-2">
                    <i class="fas fa-download"></i> Descargar plantilla CSV
                </a>

            </div>
        </div>
    </div>
@endsection
