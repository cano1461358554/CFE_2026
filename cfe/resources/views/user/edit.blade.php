@extends('layouts.app')

@section('template_title')
    {{ __('Actualizar Usuario') }}
@endsection

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-gradient-primary text-black">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0 font-weight-light">
                                <i class="fas fa-user-edit mr-2"></i> {{ __('Actualizar Usuario') }}
                            </h4>
                            <a href="{{ route('users.index') }}" class="btn btn-light text-primary">
                                <i class="fas fa-arrow-left mr-1"></i> {{ __('Volver') }}
                            </a>
                        </div>
                    </div>

                    <div class="card-body px-4">
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                <strong>{{ __('Por favor corrige los siguientes errores:') }}</strong>
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('users.update', $user->id) }}" role="form" class="needs-validation" novalidate>
                            @method('PATCH')
                            @csrf

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nombre" class="form-label">{{ __('Nombre') }}</label>
                                    <input type="text"
                                           class="form-control @error('nombre') is-invalid @enderror"
                                           id="nombre"
                                           name="nombre"
                                           value="{{ old('nombre', $user->nombre) }}"
                                           required
                                           autofocus>
                                    @error('nombre')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="apellido" class="form-label">{{ __('Apellido') }}</label>
                                    <input type="text"
                                           class="form-control @error('apellido') is-invalid @enderror"
                                           id="apellido"
                                           name="apellido"
                                           value="{{ old('apellido', $user->apellido) }}"
                                           required>
                                    @error('apellido')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="RP" class="form-label">{{ __('RP') }}</label>
                                    <input type="text"
                                           class="form-control @error('RP') is-invalid @enderror"
                                           id="RP"
                                           name="RP"
                                           value="{{ old('RP', $user->RP) }}"
                                           required>
                                    @error('RP')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="tipo_usuario" class="form-label">{{ __('Tipo de Usuario') }}</label>
                                    <select class="form-select @error('tipo_usuario') is-invalid @enderror"
                                            id="tipo_usuario"
                                            name="tipo_usuario"
                                            required>
                                        <option value="" disabled>{{ __('Seleccione un tipo') }}</option>
                                        <option value="admin" {{ (old('tipo_usuario', $user->tipo_usuario) == 'admin') ? 'selected' : '' }}>{{ __('Administrador') }}</option>
                                        <option value="encargado" {{ (old('tipo_usuario', $user->tipo_usuario) == 'encargado') ? 'selected' : '' }}>{{ __('Encargado') }}</option>
                                        <option value="empleado" {{ (old('tipo_usuario', $user->tipo_usuario) == 'empleado') ? 'selected' : '' }}>{{ __('Empleado') }}</option>
                                    </select>
                                    @error('tipo_usuario')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label">{{ __('Nueva Contraseña') }}</label>
                                    <div class="input-group">
                                        <input type="password"
                                               class="form-control @error('password') is-invalid @enderror"
                                               id="password"
                                               name="password"
                                               placeholder="{{ __('Dejar en blanco para no cambiar') }}"
                                               minlength="8">
                                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <small class="text-muted">{{ __('Mínimo 8 caracteres (opcional)') }}</small>
                                    @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="password_confirmation" class="form-label">{{ __('Confirmar Nueva Contraseña') }}</label>
                                    <input type="password"
                                           class="form-control"
                                           id="password_confirmation"
                                           name="password_confirmation"
                                           placeholder="{{ __('Confirmar nueva contraseña') }}">
                                </div>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                                <button type="reset" class="btn btn-outline-secondary me-md-2">
                                    <i class="fas fa-undo mr-1"></i> {{ __('Restablecer') }}
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save mr-1"></i> {{ __('Actualizar Usuario') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(function () {
            // Validación de formulario
            (function () {
                'use strict'

                var forms = document.querySelectorAll('.needs-validation')

                Array.prototype.slice.call(forms)
                    .forEach(function (form) {
                        form.addEventListener('submit', function (event) {
                            if (!form.checkValidity()) {
                                event.preventDefault()
                                event.stopPropagation()
                            }

                            form.classList.add('was-validated')
                        }, false)
                    })
            })()

            // Mostrar/ocultar contraseña
            $('#togglePassword').click(function(){
                const password = $('#password');
                const type = password.attr('type') === 'password' ? 'text' : 'password';
                password.attr('type', type);
                $(this).find('i').toggleClass('fa-eye fa-eye-slash');
            });
        });
    </script>
@endsection
