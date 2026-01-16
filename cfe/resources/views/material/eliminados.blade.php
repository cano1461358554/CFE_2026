@extends('layouts.app')

@section('title', 'Materiales Eliminados - Sistema de Gestión de Materiales')

@section('content')
    <div class="container-fluid px-4">
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h1 class="h3 text-gray-800">
                        <i class="fas fa-trash-restore mr-2"></i>Materiales Eliminados
                    </h1>
                    <a href="{{ route('materials.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Volver a Materiales
                    </a>
                </div>
                <hr class="mt-2 mb-4">
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-0">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show mx-3 mt-3" role="alert">
                                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-hover table-striped mb-0">
                                <thead class="thead-light">
                                <tr>
                                    <th class="w-25">Nombre</th>
                                    <th class="w-25">Stock Eliminado</th>
                                    <th class="w-20">Eliminado el</th>
                                    <th class="w-30 text-center">Acciones</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($materiales as $material)
                                    <tr>
                                        <td class="align-middle font-weight-bold">{{ $material->nombre }}</td>
                                        <td class="align-middle">
                                            @if($material->stocks->count() > 0)
                                                <div class="d-flex flex-wrap gap-2">
                                                    @foreach($material->stocks as $stock)
                                                        <span class="badge badge-pill badge-primary py-2 px-3">
                                                                {{ $stock->almacen->nombre }}: {{ $stock->cantidad }}
                                                            </span>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="text-muted font-italic">Sin stock registrado</span>
                                            @endif
                                        </td>
                                        <td class="align-middle">
                                                <span class="text-muted">
                                                    {{ $material->deleted_at->format('d/m/Y') }}
                                                </span>
                                        </td>
                                        <td class="align-middle text-center">
                                            <form action="{{ route('materials.restaurar', $material->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-sm btn-outline-success"
                                                        data-toggle="tooltip" title="Restaurar este material">
                                                    <i class="fas fa-undo-alt mr-1"></i> Restaurar
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5">
                                            <div class="alert alert-light" role="alert">
                                                <i class="fas fa-info-circle fa-lg mr-2 text-muted"></i>
                                                <span class="text-muted">No hay materiales eliminados en el sistema</span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if($materiales->count() > 0)
                            <div class="card-footer bg-white border-top-0 pt-4 pb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="text-muted small">
                                        Mostrando {{ $materiales->firstItem() }} a {{ $materiales->lastItem() }} de {{ $materiales->total() }} registros
                                    </div>
{{--                                    <div>--}}
{{--                                        {{ $materiales->links('vendor.pagination.bootstrap-4') }}--}}
{{--                                    </div>--}}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Inicializar tooltips
            $('[data-toggle="tooltip"]').tooltip({
                trigger: 'hover',
                placement: 'top'
            });

            // Confirmación antes de restaurar
            $('form[action*="restaurar"]').on('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: '¿Restaurar material?',
                    text: "¿Estás seguro de que deseas restaurar este material?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Sí, restaurar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                });
            });
        });
    </script>
@endsection

@push('styles')
    <style>
        .card {
            border-radius: 0.5rem;
            overflow: hidden;
        }
        .table thead th {
            border-top: none;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.5px;
            color: #6c757d;
        }
        .table tbody tr {
            transition: all 0.2s ease;
        }
        .table tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.02);
        }
        .badge-pill {
            border-radius: 50rem;
        }
        .gap-2 {
            gap: 0.5rem;
        }
    </style>
@endpush
