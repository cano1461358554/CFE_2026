@extends('layouts.app')

@section('content')
    <div class="content">
        <div class="container-fluid">
            <!-- Notificaciones -->
            <div class="row mb-4">
                <div class="col-12">
                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle mr-3"></i>
                                <div>{{ session('status') }}</div>
                            </div>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-exclamation-circle mr-3"></i>
                                <div>{{ session('error') }}</div>
                            </div>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    {{-- Mensaje personalizado según rol --}}
                    @role('admin')
                    <div class="alert alert-primary shadow-sm">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-user-shield mr-3 fa-lg"></i>
                            <div>
                                <strong>Bienvenido administrador.</strong>
                                <div class="small mt-1">Tienes acceso completo al sistema.</div>
                            </div>
                        </div>
                    </div>
                    @elserole('encargado')
                    <div class="alert alert-success shadow-sm">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-user-cog mr-3 fa-lg"></i>
                            <div>
                                <strong>Bienvenido encargado.</strong>
                                <div class="small mt-1">Buen trabajo, gestiona los recursos eficientemente.</div>
                            </div>
                        </div>
                    </div>
                    @elserole('empleado')
                    <div class="alert alert-info shadow-sm">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-user mr-3 fa-lg"></i>
                            <div>
                                <strong>Bienvenido empleado.</strong>
                                <div class="small mt-1">Solo podrás ver tus recursos asignados.</div>
                            </div>
                        </div>
                    </div>
                    @endrole
                </div>
            </div>

            <!-- Panel principal -->
            <div class="card card-cfe mb-4">
                <div class="card-header card-header-cfe">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0 text-white">
                            <i class="fas fa-bolt mr-2"></i>
                            {{ __('Sistema de Inventario CFE') }}
                        </h4>
                        {{--                        <span class="badge badge-light">v2.0</span>--}}
                    </div>
                </div>

                <div class="card-body">
                    <!-- Tarjetas de módulos -->
                    <div class="row">
                        <!-- Tarjeta de Movimientos -->
                        <div class="col-md-4 mb-4">
                            <div class="card card-cfe h-100">
                                <div class="card-header card-header-cfe bg-gradient-primary">
                                    <h5 class="mb-0 text-white"><i class="fas fa-exchange-alt mr-2"></i>Movimientos</h5>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group list-group-flush">
{{--                                        <li class="list-group-item border-0 px-0 py-2">--}}
{{--                                            <a href="prestamos" class="d-flex align-items-center text-decoration-none text-dark">--}}
{{--                                                <i class="fas fa-hand-holding mr-3 text-primary"></i>--}}
{{--                                                <span>Préstamos</span>--}}
{{--                                                <i class="fas fa-chevron-right ml-auto text-muted"></i>--}}
{{--                                            </a>--}}
{{--                                        </li>--}}

                                        <li class="list-group-item border-0 px-0 py-2">
                                            <a href="movimientos" class="d-flex align-items-center text-decoration-none text-dark">
                                                <i class="fas fa-hand-holding mr-3 text-primary"></i>
                                                <span>Préstamos</span>
                                                <i class="fas fa-chevron-right ml-auto text-muted"></i>
                                            </a>
                                        </li>



                                        @role('admin|encargado')
                                        @if (!auth()->user()->hasRole('empleado'))
                                            <li class="list-group-item border-0 px-0 py-2">
                                                <a href="ingresos" class="d-flex align-items-center text-decoration-none text-dark">
                                                    <i class="fas fa-sign-in-alt mr-3 text-primary"></i>
                                                    <span>Ingresos</span>
                                                    <i class="fas fa-chevron-right ml-auto text-muted"></i>
                                                </a>
                                            </li>
                                        @endif

                                        @endrole


                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Tarjeta de Materiales -->
                        <div class="col-md-4 mb-4">
                            <div class="card card-cfe h-100">
                                <div class="card-header card-header-cfe bg-gradient-success">
                                    <h5 class="mb-0 text-white"><i class="fas fa-boxes mr-2"></i>Materiales</h5>
                                </div>
                                <div class="card-body">
                                    @role('admin|encargado')
                                    <ul class="list-group list-group-flush">
                                        @if (!auth()->user()->hasRole('empleado'))
                                            <li class="list-group-item border-0 px-0 py-2">
                                                <a href="almacens" class="d-flex align-items-center text-decoration-none text-dark">
                                                    <i class="fas fa-warehouse mr-3 text-success"></i>
                                                    <span>Almacenes</span>
                                                    <i class="fas fa-chevron-right ml-auto text-muted"></i>
                                                </a>
                                            </li>
                                        @endif

                                            <li class="list-group-item border-0 px-0 py-2">
                                                <a href="ubicacions" class="d-flex align-items-center text-decoration-none text-dark">
                                                    <i class="fas fa-box-open mr-3 text-success"></i>
                                                    <span>ubicacion</span>
                                                    <i class="fas fa-chevron-right ml-auto text-muted"></i>
                                                </a>
                                            </li>

                                            <li class="list-group-item border-0 px-0 py-2">
                                                <a href="unidad-medida" class="d-flex align-items-center text-decoration-none text-dark">
                                                    <i class="fas fa-box-open mr-3 text-success"></i>
                                                    <span>Unidad de medidas</span>
                                                    <i class="fas fa-chevron-right ml-auto text-muted"></i>
                                                </a>
                                            </li>
                                    @endrole


                                        <li class="list-group-item border-0 px-0 py-2">
                                            <a href="materials" class="d-flex align-items-center text-decoration-none text-dark">
                                                <i class="fas fa-box-open mr-3 text-success"></i>
                                                <span>Materiales</span>
                                                <i class="fas fa-chevron-right ml-auto text-muted"></i>
                                            </a>
                                        </li>

                                            @role('admin|encargado')
                                            @if (!auth()->user()->hasRole('empleado'))
                                                <li class="list-group-item border-0 px-0 py-2">
                                                    <a href="stocks" class="d-flex align-items-center text-decoration-none text-dark">
                                                        <i class="fas fa-clipboard-list mr-3 text-success"></i>
                                                        <span>Stocks</span>
                                                        <i class="fas fa-chevron-right ml-auto text-muted"></i>
                                                    </a>
                                                </li>

                                                <li class="list-group-item border-0 px-0 py-2">
                                                    <a href="categorias" class="d-flex align-items-center text-decoration-none text-dark">
                                                        <i class="fas fa-box-open mr-3 text-success"></i>
                                                        <span>categorias</span>
                                                        <i class="fas fa-chevron-right ml-auto text-muted"></i>
                                                    </a>
                                                </li>

                                                <li class="list-group-item border-0 px-0 py-2">
                                                    <a href="tipo-material" class="d-flex align-items-center text-decoration-none text-dark">
                                                        <i class="fas fa-box-open mr-3 text-success"></i>
                                                        <span>Tipo Material</span>
                                                        <i class="fas fa-chevron-right ml-auto text-muted"></i>
                                                    </a>
                                                </li>

                                            @endif
                                            @endrole


                                    </ul>
                                </div>
                            </div>
                        </div>

                        @role('admin|encargado')
                        @if (!auth()->user()->hasRole('empleado'))
                            <div class="col-md-4 mb-4">
                                <div class="card card-cfe h-100">
                                    <div class="card-header card-header-cfe bg-gradient-info">
                                        <h5 class="mb-0 text-white"><i class="fas fa-tags mr-2"></i>Administración</h5>
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item border-0 px-0 py-2">
                                                <a href="users" class="d-flex align-items-center text-decoration-none text-dark">
                                                    <i class="fas fa-users mr-3 text-info"></i>
                                                    <span>Usuarios</span>
                                                    <i class="fas fa-chevron-right ml-auto text-muted"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @endrole

                    </div>

                    <!-- Información adicional -->
                    <div class="row mt-4 pt-3 border-top">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center p-3 bg-light rounded">
                                <i class="fas fa-question-circle fa-2x text-primary mr-3"></i>
                                <div>
                                    <h6 class="mb-1">¿Necesitas ayuda?</h6>
                                    <p class="small text-muted mb-0">Consulta el manual de usuario o contacta al administrador.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        :root {
            --cfe-primary: #00573F;
            --cfe-primary-light: #007A5E;
            --cfe-secondary: #2D7DD2;
            --cfe-accent: #FFC845;
            --cfe-light: #F5F5F5;
            --cfe-dark: #333333;
        }

        .card-cfe {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .card-cfe:hover {
            transform: translateY(-5px);
        }

        .card-header-cfe {
            background-color: var(--cfe-primary);
            color: white;
            border-radius: 10px 10px 0 0 !important;
        }

        .bg-gradient-primary {
            background: linear-gradient(135deg, var(--cfe-primary), var(--cfe-primary-light)) !important;
        }

        .bg-gradient-success {
            background: linear-gradient(135deg, #28a745, #218838) !important;
        }

        .bg-gradient-info {
            background: linear-gradient(135deg, #17a2b8, #138496) !important;
        }

        .list-group-item {
            transition: background-color 0.3s;
        }

        .list-group-item:hover {
            background-color: rgba(0, 87, 63, 0.05);
        }

        .alert {
            border-left: 4px solid transparent;
            border-radius: 0.375rem;
        }

        .alert-primary {
            border-left-color: var(--cfe-primary);
        }

        .alert-success {
            border-left-color: #28a745;
        }

        .alert-info {
            border-left-color: #17a2b8;
        }

        .alert-danger {
            border-left-color: #dc3545;
        }
    </style>
@endsection
