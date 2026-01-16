@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow-lg border-0 rounded-3">
                    <div class="card-header bg-primary text-white rounded-top-3">
                        <div class="d-flex justify-content-between align-items-center">
                                <a class="nav-link" href="{{ route('perfil.edit') }}">Datos Personales</a>

                            <h2 class="h5 mb-0">Cambiar Contraseña</h2>
                            <i class="fas fa-lock fa-lg"></i>
                        </div>
                    </div>


                    <div class="card-body p-4">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i>
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('perfil.update-password') }}" class="needs-validation" novalidate>
                            @csrf

                            <div class="mb-4">
                                <label for="current_password" class="form-label fw-bold">Contraseña Actual</label>
                                <div class="input-group">
                                    <input type="password" class="form-control py-2" id="current_password" name="current_password" required>
                                    <span class="input-group-text toggle-password" data-target="current_password">
                                    <i class="fas fa-eye"></i>
                                </span>
                                </div>
                                <div class="invalid-feedback">
                                    Por favor ingresa tu contraseña actual.
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="new_password" class="form-label fw-bold">Nueva Contraseña</label>
                                <div class="input-group">
                                    <input type="password" class="form-control py-2" id="new_password" name="new_password"
                                           pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$" required>
                                    <span class="input-group-text toggle-password" data-target="new_password">
                                    <i class="fas fa-eye"></i>
                                </span>
                                </div>
                                <div class="invalid-feedback">
                                    La contraseña debe tener al menos 8 caracteres, incluyendo mayúsculas, minúsculas y números.
                                </div>
                                <div class="password-strength mt-2">
                                    <div class="progress" style="height: 5px;">
                                        <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                                    </div>
                                    <small class="text-muted d-block mt-1">Seguridad de la contraseña: <span class="strength-text">Débil</span></small>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="new_password_confirmation" class="form-label fw-bold">Confirmar Nueva Contraseña</label>
                                <div class="input-group">
                                    <input type="password" class="form-control py-2" id="new_password_confirmation"
                                           name="new_password_confirmation" required>
                                    <span class="input-group-text toggle-password" data-target="new_password_confirmation">
                                    <i class="fas fa-eye"></i>
                                </span>
                                </div>
                                <div class="invalid-feedback">
                                    Las contraseñas no coinciden.
                                </div>
                            </div>

                            <div class="d-grid gap-2 mt-4">
                                <button type="submit" class="btn btn-primary py-2 fw-bold">
                                    <i class="fas fa-save me-2"></i> Actualizar Contraseña
                                </button>
                                <a href="{{ route('perfil.show') }}" class="btn btn-outline-secondary py-2">
                                    <i class="fas fa-times me-2"></i> Cancelar
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <small class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        Asegúrate de que tu contraseña sea segura y no la compartas con nadie.
                    </small>
                </div>
            </div>
        </div>
    </div>

    <style>
        .card {
            border: none;
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card-header {
            padding: 1.25rem 1.5rem;
        }

        .form-control {
            border-radius: 0.375rem;
            border: 1px solid #ced4da;
            transition: border-color 0.3s ease;
        }

        .form-control:focus {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }

        .toggle-password {
            cursor: pointer;
            background-color: #f8f9fa;
            transition: background-color 0.3s ease;
        }

        .toggle-password:hover {
            background-color: #e9ecef;
        }

        .btn-primary {
            background-color: #0d6efd;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #0b5ed7;
            transform: translateY(-1px);
        }
    </style>

    <script>
        // Mostrar/ocultar contraseña
        document.querySelectorAll('.toggle-password').forEach(function(element) {
            element.addEventListener('click', function() {
                const target = this.getAttribute('data-target');
                const input = document.getElementById(target);
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

        // Validación de contraseña en tiempo real
        const newPassword = document.getElementById('new_password');
        const confirmPassword = document.getElementById('new_password_confirmation');
        const progressBar = document.querySelector('.progress-bar');
        const strengthText = document.querySelector('.strength-text');

        if (newPassword) {
            newPassword.addEventListener('input', function() {
                const password = this.value;
                let strength = 0;

                // Verificar longitud
                if (password.length >= 8) strength++;
                if (password.length >= 12) strength++;

                // Verificar caracteres especiales
                if (/[A-Z]/.test(password)) strength++;
                if (/[a-z]/.test(password)) strength++;
                if (/[0-9]/.test(password)) strength++;
                if (/[^A-Za-z0-9]/.test(password)) strength++;

                // Actualizar barra de progreso
                const width = strength * 20;
                progressBar.style.width = `${width}%`;

                // Cambiar color y texto según la fuerza
                if (strength < 3) {
                    progressBar.className = 'progress-bar bg-danger';
                    strengthText.textContent = 'Débil';
                } else if (strength < 5) {
                    progressBar.className = 'progress-bar bg-warning';
                    strengthText.textContent = 'Moderada';
                } else {
                    progressBar.className = 'progress-bar bg-success';
                    strengthText.textContent = 'Fuerte';
                }

                // Validar confirmación de contraseña
                if (confirmPassword.value !== '') {
                    validatePasswordMatch();
                }
            });

            confirmPassword.addEventListener('input', validatePasswordMatch);
        }

        function validatePasswordMatch() {
            if (newPassword.value !== confirmPassword.value) {
                confirmPassword.setCustomValidity('Las contraseñas no coinciden');
            } else {
                confirmPassword.setCustomValidity('');
            }
        }

        // Validación de formulario
        (function() {
            'use strict';

            const forms = document.querySelectorAll('.needs-validation');

            Array.from(forms).forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }

                    form.classList.add('was-validated');
                }, false);
            });
        })();
    </script>
