@extends('layouts.app')

@section('content')
    <div class="auth-page">
        <div class="register-container">
            <div class="register-header">
                <div class="logo-container">
                    <div class="logo-circle">
                        <i class="fas fa-user-plus logo-icon"></i>
                    </div>
                </div>
                <h2>{{ __('Crear Cuenta') }}</h2>
                <p>{{ __('Regístrate para acceder al sistema') }}</p>
            </div>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <div class="input-with-icon">
                            <i class="fas fa-user input-icon"></i>
                            <input id="nombre" type="text" class="form-control @error('nombre') is-invalid @enderror"
                                   name="nombre" value="{{ old('nombre') }}" required autocomplete="nombre" autofocus
                                   placeholder="{{ __('Nombre') }}">
                        </div>
                        @error('nombre')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group col-md-6">
                        <div class="input-with-icon">
                            <i class="fas fa-user input-icon"></i>
                            <input id="apellido" type="text" class="form-control @error('apellido') is-invalid @enderror"
                                   name="apellido" value="{{ old('apellido') }}" required autocomplete="apellido"
                                   placeholder="{{ __('Apellido') }}">
                        </div>
                        @error('apellido')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <div class="input-with-icon">
                        <i class="fas fa-id-card input-icon"></i>
                        <input id="RP" type="text" class="form-control @error('RP') is-invalid @enderror"
                               name="RP" value="{{ old('RP') }}" required autocomplete="RP"
                               placeholder="{{ __('Número de RP') }}">
                    </div>
                    <small class="form-text text-muted">Identificador único para iniciar sesión</small>
                    @error('RP')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <div class="input-with-icon">
                        <i class="fas fa-user-tag input-icon"></i>
                        <select id="tipo_usuario" class="form-control @error('tipo_usuario') is-invalid @enderror"
                                name="tipo_usuario" required>
                            <option value="">Tipo de Usuario</option>
                            <option value="encargado" {{ old('tipo_usuario') == 'encargado' ? 'selected' : '' }}>Encargado</option>
                            <option value="empleado" {{ old('tipo_usuario') == 'empleado' ? 'selected' : '' }}>Empleado</option>
                        </select>
                    </div>
                    @error('tipo_usuario')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <div class="input-with-icon">
                            <i class="fas fa-lock input-icon"></i>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                                   name="password" required autocomplete="new-password"
                                   placeholder="{{ __('Contraseña') }}">
                        </div>
                        @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group col-md-6">
                        <div class="input-with-icon">
                            <i class="fas fa-lock input-icon"></i>
                            <input id="password-confirm" type="password" class="form-control"
                                   name="password_confirmation" required autocomplete="new-password"
                                   placeholder="{{ __('Confirmar Contraseña') }}">
                        </div>
                    </div>
                </div>

                <div class="password-requirements">
                    <p class="requirement-title">La contraseña debe contener:</p>
                    <ul>
                        <li>Mínimo 8 caracteres</li>
                        <li>Al menos una letra mayúscula</li>
                        <li>Al menos un número</li>
                        <li>Al menos un carácter especial</li>
                    </ul>
                </div>

                <button type="submit" class="btn btn-register">
                    <i class="fas fa-user-plus"></i> {{ __('Crear Cuenta') }}
                </button>

                <div class="login-footer">
                    {{ __('¿Ya tienes una cuenta?') }} <a href="{{ route('login') }}">{{ __('Inicia sesión') }}</a>
                </div>
            </form>
        </div>
    </div>

    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #00573F 0%, #007A5E 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            color: #333;
        }

        .auth-page {
            width: 100%;
            max-width: 500px;
        }

        .register-container {
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            padding: 35px;
            position: relative;
        }

        .register-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #00573F, #00C897);
        }

        .register-header {
            text-align: center;
            margin-bottom: 30px;
            position: relative;
        }

        .logo-container {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .logo-circle {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #00573F, #00C897);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 5px 15px rgba(0, 87, 63, 0.3);
        }

        .logo-icon {
            font-size: 35px;
            color: white;
        }

        .register-header h2 {
            color: #00573F;
            font-weight: 700;
            font-size: 28px;
            margin-bottom: 8px;
        }

        .register-header p {
            color: #666;
            font-size: 16px;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .input-with-icon {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #00573F;
            font-size: 18px;
            z-index: 2;
        }

        .form-control {
            height: 50px;
            border-radius: 10px;
            border: 2px solid #ddd;
            padding-left: 45px;
            transition: all 0.3s;
            font-size: 16px;
            box-shadow: none;
            width: 100%;
        }

        .form-control:focus {
            border-color: #00573F;
            box-shadow: 0 0 0 3px rgba(0, 87, 63, 0.15);
        }

        select.form-control {
            height: 50px;
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 15px center;
            background-size: 15px;
        }

        .btn-register {
            background: linear-gradient(135deg, #00573F, #00C897);
            color: white;
            border: none;
            height: 55px;
            border-radius: 10px;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s;
            cursor: pointer;
            margin-top: 15px;
            font-size: 18px;
            letter-spacing: 0.5px;
            box-shadow: 0 5px 15px rgba(0, 87, 63, 0.3);
        }

        .btn-register:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 87, 63, 0.4);
        }

        .btn-register:active {
            transform: translateY(1px);
        }

        .login-footer {
            text-align: center;
            margin-top: 25px;
            color: #666;
            font-size: 15px;
        }

        .login-footer a {
            color: #00573F;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
        }

        .login-footer a:hover {
            color: #00C897;
            text-decoration: underline;
        }

        .invalid-feedback {
            color: #e74c3c;
            font-size: 14px;
            margin-top: 5px;
            display: block;
            font-weight: 500;
        }

        .form-text {
            font-size: 13px;
            color: #666;
            margin-top: 5px;
            padding-left: 10px;
        }

        .password-requirements {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            margin-top: 10px;
            border-left: 4px solid #00573F;
        }

        .requirement-title {
            font-weight: 600;
            color: #00573F;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .password-requirements ul {
            margin: 0;
            padding-left: 20px;
            font-size: 13px;
            color: #666;
        }

        .password-requirements li {
            margin-bottom: 5px;
        }

        @media (max-width: 768px) {
            .register-container {
                padding: 25px;
            }

            .form-row {
                flex-direction: column;
            }

            .form-group {
                width: 100%;
            }

            .register-header h2 {
                font-size: 24px;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Validación en tiempo real de la contraseña
            const passwordInput = document.getElementById('password');

            if(passwordInput) {
                passwordInput.addEventListener('input', function() {
                    const password = this.value;

                    // Solo para demostración, en producción usa la validación del servidor
                    console.log("Validando contraseña:", password);
                });
            }
        });
    </script>
@endsection
