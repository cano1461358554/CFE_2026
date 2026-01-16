@extends('layouts.app')

@section('content')
    <div class="auth-page">
        <div class="login-container">
            <div class="login-header">
                <div class="logo-container">
                    <div class="logo-circle">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="white" width="40px" height="40px">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/>
                        </svg>
                    </div>
                </div>
                <h2>Iniciar Sesión</h2>
                <p class="subtitle">Ingresa tus credenciales para acceder al sistema</p>
            </div>

            <form method="POST" action="{{ route('login') }}" class="login-form">
                @csrf

                <!-- Mensaje de error general -->
                @if($errors->any())
                    <div class="alert alert-danger" role="alert">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                        </svg>
                        <span>Credenciales incorrectas. Verifica tu RP y contraseña.</span>
                    </div>
                @endif

                <!-- Campo RP -->
                <div class="form-group input-with-icon">
                    <i class="fas fa-id-card input-icon"></i>
                    <input id="RP" type="text" class="form-control @error('RP') is-invalid @enderror"
                           name="RP" value="{{ old('RP') }}" required autocomplete="RP" autofocus
                           placeholder="Ejemplo: 9JFN56">
                    @error('RP')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <!-- Campo Contraseña -->
                <div class="form-group input-with-icon">
                    <i class="fas fa-lock input-icon"></i>
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                           name="password" required autocomplete="current-password"
                           placeholder="Contraseña">
                    @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <!-- Opciones adicionales -->
                <div class="form-options">
                    <div class="remember-me">
                        <label class="checkbox-container">
                            <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <span class="checkmark"></span>
                            <span class="checkbox-label">Recuérdame</span>
                        </label>
                    </div>
                    <div class="forgot-password">
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-link">
                                ¿Olvidaste tu contraseña?
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Botón de envío -->
                <button type="submit" class="btn btn-login">
                    <span>Iniciar sesión</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z"/>
                    </svg>
                </button>

                <!-- Enlace a registro -->
                @if (Route::has('register'))
                    <div class="login-footer">
                        ¿No tienes una cuenta? <a href="{{ route('register') }}" class="text-link">Regístrate</a>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <!-- Estilos optimizados -->
    <style>
        :root {
            --primary-color: #00573F;
            --primary-dark: #003D2C;
            --primary-light: #00C897;
            --error-color: #dc3545;
            --text-color: #333;
            --text-light: #666;
            --border-color: #ddd;
            --background-light: #f5f5f5;
            --white: #ffffff;
            --transition: all 0.3s ease;
        }

        .auth-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
        }

        .login-container {
            width: 100%;
            max-width: 28rem;
            padding: 2.5rem;
            background-color: var(--white);
            border-radius: 1rem;
            box-shadow: 0 1.5rem 3rem rgba(0, 0, 0, 0.15);
        }

        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .logo-circle {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
        }

        .input-with-icon {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary-color);
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 3rem;
            border: 2px solid var(--border-color);
            border-radius: 0.5rem;
            transition: var(--transition);
            font-size: 1rem;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(0, 87, 63, 0.1);
            outline: none;
        }

        .is-invalid {
            border-color: var(--error-color) !important;
        }

        .invalid-feedback {
            color: var(--error-color);
            font-size: 0.875rem;
            margin-top: 0.25rem;
            display: block;
        }

        .alert-danger {
            background-color: rgba(220, 53, 69, 0.1);
            border-left: 4px solid var(--error-color);
            padding: 0.75rem 1rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--error-color);
        }

        .btn-login {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            color: white;
            padding: 0.75rem;
            border-radius: 0.5rem;
            border: none;
            width: 100%;
            margin-top: 1rem;
            transition: var(--transition);
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 87, 63, 0.3);
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 1rem 0;
        }

        .checkbox-container {
            display: flex;
            align-items: center;
            cursor: pointer;
        }

        .checkmark {
            display: inline-block;
            width: 1.25rem;
            height: 1.25rem;
            border: 2px solid var(--border-color);
            border-radius: 0.25rem;
            margin-right: 0.5rem;
            position: relative;
        }

        .checkbox-container input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
        }

        .checkbox-container input:checked ~ .checkmark {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .text-link {
            color: var(--primary-color);
            text-decoration: none;
            transition: var(--transition);
        }

        .text-link:hover {
            text-decoration: underline;
        }

        .login-footer {
            text-align: center;
            margin-top: 1.5rem;
            color: var(--text-light);
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 1.5rem;
                margin: 0.75rem;
            }
        }
    </style>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endsection
