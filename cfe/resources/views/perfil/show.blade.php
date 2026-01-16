@extends('layouts.app')

@section('template_title')
    {{ __('Perfil de Usuario') }} - {{ $profile->nombre }} {{ $profile->apellido }}
@endsection

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card border-0 shadow-lg">
                    <!-- Encabezado con estilo profesional -->
                    <div class="card-header bg-blue-700  text-black py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">
                                <i class="fas fa-user-cog mr-2"></i> Perfil y Configuración
                            </h4>
                            <span class="badge bg-white text-primary">
                            {{ ucfirst($user->tipo_usuario) }}
                        </span>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        <!-- Sección de información del perfil -->
                        <div class="text-center mb-4">
                            <div class="avatar-container mb-3">
                                <div class="avatar-circle bg-primary text-white">
                                    {{ substr($profile->nombre, 0, 1) }}{{ substr($profile->apellido, 0, 1) }}
                                </div>
                            </div>
                            <h4 class="mb-1">{{ $profile->nombre }} {{ $profile->apellido }}</h4>
                            <p class="text-muted mb-3">
                                <i class="fas fa-id-badge mr-1"></i> {{ $user->RP }}
                            </p>
                        </div>

                        <!-- Detalles del perfil -->
                        <div class="profile-details mb-4">
                            <div class="detail-item">
                                <div class="detail-icon text-primary">
                                    <i class="fas fa-building"></i>
                                </div>
                                <div class="detail-content">
                                    <h6 class="detail-title">Área/Departamento</h6>
                                    <p class="detail-text">{{ $profile->area ?? 'No especificada' }}</p>
                                </div>
                            </div>

                            <div class="detail-item">
                                <div class="detail-icon text-primary">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div class="detail-content">
                                    <h6 class="detail-title">Correo Electrónico</h6>
                                    <p class="detail-text">{{ $profile->gmail ?? 'No especificado' }}</p>
                                </div>
                            </div>

                            <!-- Puedes agregar más detalles aquí según sea necesario -->
                        </div>

                        <!-- Botón de edición -->
                        <div class="text-center mt-4">
                            <a href="{{ route('perfil.edit') }}" class="btn btn-primary btn-lg px-4 shadow-sm">
                                <i class="fas fa-user-edit mr-2"></i> Editar Perfil
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        .bg-gradient-primary {
            background: linear-gradient(135deg, #00723E 0%, #00A86B 100%);
        }

        .card {
            border-radius: 0.5rem;
            overflow: hidden;
        }

        .avatar-container {
            display: flex;
            justify-content: center;
        }

        .avatar-circle {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            font-weight: 600;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .profile-details {
            background-color: #f8f9fa;
            border-radius: 0.5rem;
            padding: 1.5rem;
        }

        .detail-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 1.5rem;
        }

        .detail-item:last-child {
            margin-bottom: 0;
        }

        .detail-icon {
            font-size: 1.5rem;
            margin-right: 1rem;
            min-width: 40px;
            text-align: center;
        }

        .detail-content {
            flex: 1;
        }

        .detail-title {
            font-weight: 600;
            color: #343a40;
            margin-bottom: 0.25rem;
        }

        .detail-text {
            color: #6c757d;
            margin-bottom: 0;
        }

        .btn-primary {
            background-color: #00723E;
            border-color: #00723E;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            background-color: #005a32;
            transform: translateY(-2px);
        }

        .badge {
            font-weight: 500;
            letter-spacing: 0.5px;
            padding: 0.5rem 0.75rem;
        }
    </style>
@endsection
