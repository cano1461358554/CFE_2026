<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'RP' => 'required|string',
            'password' => 'required|string',
        ]);

        // Normalizar el RP (eliminar espacios y convertir a mayúsculas)
        $rp = Str::upper(Str::replace(' ', '', $request->RP));

        // Buscar usuario ignorando espacios y mayúsculas/minúsculas
        $user = User::whereRaw("UPPER(REPLACE(RP, ' ', '')) = ?", [$rp])->first();

        if (!$user) {
            return back()->withErrors([
                'RP' => 'Las credenciales no coinciden con nuestros registros.',
            ])->withInput();
        }

        // Verificación de contraseña mejorada
        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'password' => 'La contraseña es incorrecta.',
            ])->withInput();
        }

        // Autenticar al usuario
        Auth::login($user, $request->filled('remember'));

        return redirect()->intended('/home');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function username()
    {
        return 'RP';
    }
    protected function attemptLogin(Request $request)
    {
        // Elimina espacios y convierte a mayúsculas
        $rp = strtoupper(trim($request->RP));

        return Auth::attempt(
            ['RP' => $rp, 'password' => $request->password],
            $request->filled('remember')
        );
    }
}
