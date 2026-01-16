@extends('layouts.app')

@section('template_title')
    {{ __('Editar Perfil') }} - {{ $profile->nombre }} {{ $profile->apellido }}
@endsection

@section('content')
    <div class="container-fluid px-4 py-5">
        <div class="row justify-content-center">
            <div class="col-xl-8 col-lg-10">
                <div class="card border-0 shadow-lg rounded-3 overflow-hidden">
                    <!-- Encabezado con gradiente profesional -->
                    <div class="card-header bg-gradient-primary text-black py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="mb-0">
                                <i class="fas fa-user-edit me-2"></i> Actualizar Perfil
                            </h3>
                            <span class="badge bg-white text-primary">
                                {{ ucfirst($user->tipo_usuario) }}
                            </span>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <!-- Botones para cambiar entre secciones -->
                        <div class="mb-4">
                            <button onclick="showSection('profile')" class="btn btn-outline-primary me-2 active-section">
                                <i class="fas fa-user me-1"></i> Información Personal
                            </button>
                            <button onclick="showSection('password')" class="btn btn-outline-primary">

                                <a href="{{ route('perfil.change-password') }}" >Cambiar Contraseña</a>

                            </button>

                        </div>

                        <!-- Sección de Información Personal -->
                        <div id="profile-section">
                            <form action="{{ route('perfil.update') }}" method="POST" class="needs-validation" novalidate>
                                @csrf
                                @method('PUT')

                                <div class="row g-4 mb-4">
                                    <!-- Nombre -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label fw-bold text-dark">
                                                <i class="fas fa-user me-1 text-primary"></i> Nombre <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" name="nombre" id="nombre"
                                                   class="form-control @error('nombre') is-invalid @enderror"
                                                   value="{{ old('nombre', $profile->nombre) }}"
                                                   placeholder="Ingrese su nombre" required>
                                            @error('nombre')
                                            <div class="invalid-feedback">
                                                <i class="fas fa-exclamation-circle me-1"></i> {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Apellido -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label fw-bold text-dark">
                                                <i class="fas fa-user-tag me-1 text-primary"></i> Apellido <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" name="apellido" id="apellido"
                                                   class="form-control @error('apellido') is-invalid @enderror"
                                                   value="{{ old('apellido', $profile->apellido) }}"
                                                   placeholder="Ingrese su apellido" required>
                                            @error('apellido')
                                            <div class="invalid-feedback">
                                                <i class="fas fa-exclamation-circle me-1"></i> {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Área/Departamento -->
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="form-label fw-bold text-dark">
                                                <i class="fas fa-building me-1 text-primary"></i> Área/Departamento <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" name="area" id="area"
                                                   class="form-control @error('area') is-invalid @enderror"
                                                   value="{{ old('area', $profile->area) }}"
                                                   placeholder="Ingrese su área o departamento" required>
                                            @error('area')
                                            <div class="invalid-feedback">
                                                <i class="fas fa-exclamation-circle me-1"></i> {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Correo Alternativo -->
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="form-label fw-bold text-dark">
                                                <i class="fas fa-envelope me-1 text-primary"></i> Correo Alternativo (Gmail)
                                            </label>
                                            <input type="email" name="gmail" id="gmail"
                                                   class="form-control @error('gmail') is-invalid @enderror"
                                                   value="{{ old('gmail', $profile->gmail) }}"
                                                   placeholder="Ingrese su correo alternativo">
                                            <small class="form-text text-muted">
                                                Este correo se usará como respaldo para notificaciones.
                                            </small>
                                            @error('gmail')
                                            <div class="invalid-feedback">
                                                <i class="fas fa-exclamation-circle me-1"></i> {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Botones de acción -->
                                <div class="d-flex justify-content-between pt-3 border-top">
                                    <a href="{{ route('perfil.show') }}" class="btn btn-outline-secondary px-4 rounded-pill">
                                        <i class="fas fa-times me-2"></i> Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-primary px-4 rounded-pill shadow-sm">
                                        <i class="fas fa-save me-2"></i> Guardar Cambios
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Sección de Cambio de Contraseña (inicialmente oculta) -->
                        <div id="password-section" style="display: none;">
                            <form action="{{ route('perfil.update-password') }}" method="POST" class="needs-validation" novalidate>
                                @csrf
                                @method('PUT')

                                @if(session('success'))
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                @endif

                                @if($errors->any())
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <i class="fas fa-exclamation-triangle me-2"></i> Por favor corrige los siguientes errores:
                                        <ul class="mb-0">
                                            @foreach($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                @endif

                                <div class="row g-4 mb-4">
                                    <!-- Contraseña Actual -->
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="form-label fw-bold text-dark">
                                                <i class="fas fa-lock me-1 text-primary"></i> Contraseña Actual <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <input type="password" name="current_password" id="current_password"
                                                       class="form-control @error('current_password') is-invalid @enderror"
                                                       placeholder="Ingrese su contraseña actual" required>
                                                <button class="btn btn-outline-secondary toggle-password" type="button" data-target="current_password">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                            @error('current_password')
                                            <div class="invalid-feedback d-block">
                                                <i class="fas fa-exclamation-circle me-1"></i> {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Nueva Contraseña -->
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="form-label fw-bold text-dark">
                                                <i class="fas fa-key me-1 text-primary"></i> Nueva Contraseña <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <input type="password" name="new_password" id="new_password"
                                                       class="form-control @error('new_password') is-invalid @enderror"
                                                       placeholder="Ingrese su nueva contraseña" required
                                                       pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$">
                                                <button class="btn btn-outline-secondary toggle-password" type="button" data-target="new_password">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                            <small class="form-text text-muted">
                                                Mínimo 8 caracteres, incluyendo mayúsculas, minúsculas y números.
                                            </small>
                                            <div id="password-strength" class="mt-2"></div>
                                            @error('new_password')
                                            <div class="invalid-feedback d-block">
                                                <i class="fas fa-exclamation-circle me-1"></i> {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Confirmar Nueva Contraseña -->
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="form-label fw-bold text-dark">
                                                <i class="fas fa-key me-1 text-primary"></i> Confirmar Nueva Contraseña <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <input type="password" name="new_password_confirmation" id="new_password_confirmation"
                                                       class="form-control @error('new_password_confirmation') is-invalid @enderror"
                                                       placeholder="Confirme su nueva contraseña" required>
                                                <button class="btn btn-outline-secondary toggle-password" type="button" data-target="new_password_confirmation">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                            @error('new_password_confirmation')
                                            <div class="invalid-feedback d-block">
                                                <i class="fas fa-exclamation-circle me-1"></i> {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Botones de acción -->
                                <div class="d-flex justify-content-between pt-3 border-top">
                                    <a href="{{ route('perfil.show') }}" class="btn btn-outline-secondary px-4 rounded-pill">
                                        <i class="fas fa-times me-2"></i> Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-primary px-4 rounded-pill shadow-sm">
                                        <i class="fas fa-save me-2"></i> Actualizar Contraseña
                                    </button>
                                </div>
                            </form>
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
            border-radius: 0.5rem !important;
        }

        .form-control {
            border-radius: 0.375rem;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: #00723E;
            box-shadow: 0 0 0 0.2rem rgba(0, 114, 62, 0.25);
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

        .btn-outline-secondary:hover {
            background-color: #f8f9fa;
        }

        .rounded-pill {
            border-radius: 50rem !important;
        }

        .badge {
            font-weight: 500;
            letter-spacing: 0.5px;
            padding: 0.5rem 0.75rem;
        }

        .form-label {
            margin-bottom: 0.5rem;
        }

        .invalid-feedback {
            font-size: 0.85rem;
        }

        /* Estilos para los botones de sección */
        .btn-outline-primary.active-section {
            background-color: #00723E;
            color: white;
        }

        /* Estilos para el indicador de fortaleza de contraseña */
        .password-strength {
            height: 5px;
            border-radius: 3px;
            margin-top: 5px;
            transition: all 0.3s;
        }

        .password-strength-weak {
            background-color: #dc3545;
            width: 25%;
        }

        .password-strength-medium {
            background-color: #fd7e14;
            width: 50%;
        }

        .password-strength-good {
            background-color: #ffc107;
            width: 75%;
        }

        .password-strength-strong {
            background-color: #28a745;
            width: 100%;
        }
    </style>
@endsection

@section('scripts')
    <script>
        // Función para cambiar entre secciones
        function showSection(section) {
            // Oculta todas las secciones
            document.getElementById('profile-section').style.display = 'none';
            document.getElementById('password-section').style.display = 'none';

            // Muestra la sección seleccionada
            document.getElementById(section + '-section').style.display = 'block';

            // Actualiza los estilos de los botones
            const buttons = document.querySelectorAll('.btn-outline-primary');
            buttons.forEach(btn => {
                btn.classList.remove('active-section');
            });
            event.target.classList.add('active-section');
        }

        // Validación del formulario
        (function() {
            'use strict';
            const forms = document.querySelectorAll('.needs-validation');
            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        })();

        // Mostrar/ocultar contraseña
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const input = document.getElementById(targetId);
                const icon = this.querySelector('i');

                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        });

        // Validación de fortaleza de contraseña en tiempo real
        document.getElementById('new_password')?.addEventListener('input', function() {
            const password = this.value;
            const strengthIndicator = document.getElementById('password-strength');

            // Resetear indicador
            strengthIndicator.innerHTML = '';
            strengthIndicator.className = '';

            if (password.length === 0) return;

            // Calcular fortaleza
            let strength = 0;

            // Longitud mínima
            if (password.length >= 8) strength++;

            // Contiene mayúsculas
            if (/[A-Z]/.test(password)) strength++;

            // Contiene minúsculas
            if (/[a-z]/.test(password)) strength++;

            // Contiene números
            if (/\d/.test(password)) strength++;

            // Contiene caracteres especiales
            if (/[^A-Za-z0-9]/.test(password)) strength++;

            // Crear elemento de fortaleza
            const strengthBar = document.createElement('div');
            strengthBar.className = 'password-strength';

            const strengthText = document.createElement('small');

            let strengthClass = '';
            let strengthMessage = '';

            if (strength <= 2) {
                strengthClass = 'password-strength-weak';
                strengthMessage = 'Débil';
            } else if (strength === 3) {
                strengthClass = 'password-strength-medium';
                strengthMessage = 'Moderada';
            } else if (strength === 4) {
                strengthClass = 'password-strength-good';
                strengthMessage = 'Buena';
            } else {
                strengthClass = 'password-strength-strong';
                strengthMessage = 'Fuerte';
            }

            strengthBar.classList.add(strengthClass);
            strengthText.textContent = strengthMessage;
            strengthText.className = 'ms-2 ' + strengthClass.replace('password-strength-', 'text-');

            strengthIndicator.appendChild(strengthBar);
            strengthIndicator.appendChild(strengthText);
        });

        // Validar coincidencia de contraseñas
        document.getElementById('new_password_confirmation')?.addEventListener('input', function() {
            const password = document.getElementById('new_password').value;
            const confirmation = this.value;

            if (confirmation.length === 0) return;

            if (password !== confirmation) {
                this.setCustomValidity('Las contraseñas no coinciden');
            } else {
                this.setCustomValidity('');
            }
        });
    </script>
@endsection
