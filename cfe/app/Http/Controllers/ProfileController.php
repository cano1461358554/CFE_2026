<?php

namespace App\Http\Controllers;  // Agrega el namespace correcto

use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;  // Importa la clase Controller base
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{

    // Mostrar perfil del usuario actual
    public function show()
    {
        $user = Auth::user();
        $user->load('profile'); // Carga eager del perfil

        // Si no existe perfil, crea uno básico
        if (!$user->profile) {
            $user->profile()->create([
                'nombre' => $user->nombre,
                'apellido' => $user->apellido,
                'area' => null,
                'gmail' => null
            ]);
            $user->refresh(); // Recargar la relación
        }

        return view('perfil.show', [
            'user' => $user,
            'profile' => $user->profile
        ]);
    }

    // Mostrar formulario para editar
    public function edit()
    {
        $user = Auth::user();
        $user->load('profile');

        if (!$user->profile) {
            $user->profile()->create([
                'nombre' => $user->nombre,
                'apellido' => $user->apellido,
                'area' => null,
                'gmail' => null
            ]);
            $user->refresh();
        }

        return view('perfil.edit', [
            'user' => $user,
            'profile' => $user->profile
        ]);
    }

    // Actualizar perfil
    public function update(Request $request)
    {
        $request->validate([
            'nombre' => 'nullable|string|max:255',
            'apellido' => 'nullable|string|max:255',
            'area' => 'nullable|string|max:255',
            'gmail' => 'nullable|email|max:255',
        ]);

        $user = Auth::user();
        $profile = $user->profile;

        // Actualizar campos del perfil
        $profile->nombre = $request->input('nombre', $profile->nombre);
        $profile->apellido = $request->input('apellido', $profile->apellido);
        $profile->area = $request->input('area', $profile->area);
        $profile->gmail = $request->input('gmail', $profile->gmail);


        $profile->save();

        return redirect()->route('perfil.show')
            ->with('success', 'Perfil actualizado correctamente.');


    }
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'La contraseña actual es incorrecta']);
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with('success', 'Contraseña actualizada correctamente');
    }
    public function showChangePasswordForm() {
        return view('perfil.change-password');
    }
//    public function updatePassword(Request $request)
//    {
//        $request->validate([
//            'current_password' => ['required', 'current_password'],
//            'new_password' => [
//                'required',
//                'confirmed',
//                Password::min(8)
//                    ->mixedCase()
//                    ->numbers()
//            ],
//        ]);
//
//        $user = $request->user();
//        $user->password = Hash::make($request->new_password);
//        $user->save();
//
//        return redirect()->route('perfil.show')
//            ->with('success', 'Contraseña actualizada correctamente');
//    }
}
