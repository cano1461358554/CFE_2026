@extends('layouts.app')

@section('template_title')
    {{ __('Gestión de Usuarios') }}
@endsection

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-gradient-primary text-black">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0 font-weight-light">
                                <i class="fas fa-users-cog mr-2"></i> {{ __('Gestión de Usuarios') }}
                            </h4>
                            @role('admin|encargado')
                            <a href="{{ route('users.create') }}" class="btn btn-light text-primary">
                                <i class="fas fa-user-plus mr-1"></i> {{ __('Nuevo Usuario') }}
                            </a>
{{--                            <a href="{{ route('user.import.form') }}" class="btn btn-primary" title="Importar usuarios">--}}
{{--                                <i class="fas fa-file-import"></i> Importar--}}
{{--                            </a>--}}

                            @endrole
                        </div>
                    </div>

                    <div class="card-body px-4">
                        @if ($message = Session::get('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle mr-2"></i> {{ $message }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        <form method="GET" action="{{ route('users.index') }}" class="mb-4">
                            <div class="row align-items-end">
                                <div class="col-md-5 mb-3">
                                    <label for="nombre" class="form-label">Buscar por nombre</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light">
                                                <i class="fas fa-search text-primary"></i>
                                            </span>
                                        </div>
                                        <input type="text"
                                               name="nombre"
                                               id="nombre"
                                               class="form-control"
                                               placeholder="Ingrese nombre a buscar..."
                                               value="{{ request('nombre') }}">
                                    </div>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <div class="form-check">
                                        <input type="checkbox"
                                               class="form-check-input"
                                               id="mostrar_coincidencias"
                                               name="mostrar_coincidencias"
                                               {{ request('mostrar_coincidencias') ? 'checked' : '' }}
                                               onchange="this.form.submit()">
                                        <label class="form-check-label" for="mostrar_coincidencias">Mostrar solo coincidencias</label>
                                    </div>
                                </div>

                                <div class="col-md-3 mb-3 text-right">
                                    <button type="submit" class="btn btn-primary mr-2">
                                        <i class="fas fa-search mr-1"></i> Buscar
                                    </button>
                                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-sync-alt mr-1"></i> Limpiar
                                    </a>
                                </div>
                            </div>
                        </form>

                        <div class="table-responsive rounded-lg">
                            <table class="table table-hover table-bordered mb-0">
                                <thead class="thead-light">
                                <tr>
{{--                                    <th class="text-center align-middle">#</th>--}}
                                    <th class="text-center align-middle">{{ __('Nombre') }}</th>
                                    <th class="text-center align-middle">{{ __('Apellido') }}</th>
                                    <th class="text-center align-middle">{{ __('RP') }}</th>
                                    <th class="text-center align-middle">{{ __('Tipo de Usuario') }}</th>
                                    @role('admin|encargado')
                                    <th class="text-center align-middle" style="width: 180px;">{{ __('Acciones') }}</th>
                                    @endrole
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $nombreBuscado = request('nombre');
                                @endphp

                                @forelse ($users as $user)
                                    @php
                                        $coincide = $nombreBuscado && stripos($user->nombre, $nombreBuscado) !== false;
                                    @endphp
                                    <tr class="{{ $coincide ? 'table-info' : '' }}">
{{--                                        <td class="align-middle text-center">{{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}</td>--}}
                                        <td class="align-middle">{{ $user->nombre }}</td>
                                        <td class="align-middle">{{ $user->apellido }}</td>
                                        <td class="align-middle text-center">{{ $user->RP }}</td>
                                        <td class="align-middle text-center">
                                            <span class="badge badge-pill bg-{{ $user->tipo_usuario == 'admin' ? 'danger' : ($user->tipo_usuario == 'encargado' ? 'warning' : 'success') }}">
                                                {{ $user->tipo_usuario }}
                                            </span>
                                        </td>

                                        @role('admin|encargado')
                                        <td class="align-middle text-center">
                                            <div class="btn-group" role="group">
{{--                                                <a href="{{ route('users.show', $user->id) }}"--}}
{{--                                                   class="btn btn-sm btn-info"--}}
{{--                                                   data-toggle="tooltip"--}}
{{--                                                   title="Ver">--}}
{{--                                                    <i class="fas fa-eye"></i>--}}
{{--                                                </a>--}}
                                                <a href="{{ route('users.edit', $user->id) }}"
                                                   class="btn btn-sm btn-warning"
                                                   data-toggle="tooltip"
                                                   title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="btn btn-sm btn-danger"
                                                            onclick="return confirm('¿Está seguro de eliminar este usuario?')"
                                                            data-toggle="tooltip"
                                                            title="Eliminar">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                        @endrole
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="@role('admin|encargado')6 @else 5 @endrole" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-user-slash fa-2x mb-2"></i>
                                                <h5>No se encontraron usuarios registrados</h5>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if($users->hasPages())
                            <div class="row mt-4">
                                <div class="col-12 d-flex justify-content-center">
                                    <nav aria-label="Page navigation">
                                        <ul class="pagination">
                                            {{-- Previous Page Link --}}
                                            @if ($users->onFirstPage())
                                                <li class="page-item disabled" aria-disabled="true">
                                                    <span class="page-link">&laquo; Anterior</span>
                                                </li>
                                            @else
                                                <li class="page-item">
                                                    <a class="page-link" href="{{ $users->previousPageUrl() }}" rel="prev">&laquo; Anterior</a>
                                                </li>
                                            @endif

                                            {{-- Pagination Elements --}}
                                            @foreach ($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                                                @if ($page == $users->currentPage())
                                                    <li class="page-item active" aria-current="page">
                                                        <span class="page-link">{{ $page }}</span>
                                                    </li>
                                                @else
                                                    <li class="page-item">
                                                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                                    </li>
                                                @endif
                                            @endforeach

                                            {{-- Next Page Link --}}
                                            @if ($users->hasMorePages())
                                                <li class="page-item">
                                                    <a class="page-link" href="{{ $users->nextPageUrl() }}" rel="next">Siguiente &raquo;</a>
                                                </li>
                                            @else
                                                <li class="page-item disabled" aria-disabled="true">
                                                    <span class="page-link">Siguiente &raquo;</span>
                                                </li>
                                            @endif
                                        </ul>
                                    </nav>
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
        $(function () {
            // Inicializar tooltips
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@endsection
