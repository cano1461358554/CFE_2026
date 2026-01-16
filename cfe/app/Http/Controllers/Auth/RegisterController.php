<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'nombre' => ['required', 'string', 'max:255'],
            'apellido' => ['required', 'string', 'max:255'],
            'RP' => ['required', 'string', 'max:255', 'unique:users'],
            'tipo_usuario' => ['required', 'string', 'in:admin,encargado,empleado'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'password.min' => 'La contraseña debe tener al menos 8 caracteres',
            'password.confirmed' => 'Las contraseñas no coinciden',
            'RP.unique' => 'Este número de RP ya está registrado',
            'tipo_usuario.in' => 'Tipo de usuario no válido',
        ]);
    }

    protected function create(array $data)
    {
        try {
            $user = User::create([
                'nombre' => $data['nombre'],
                'apellido' => $data['apellido'],
                'RP' => $data['RP'],
                'tipo_usuario' => $data['tipo_usuario'],
                'password' => Hash::make($data['password']),
            ]);

            $this->assignUserRole($user, $data['tipo_usuario']);
            $this->createUserProfile($user, $data);

            return $user;

        } catch (\Exception $e) {
            \Log::error('Error al crear usuario: '.$e->getMessage());
            throw $e; // Re-lanzar la excepción para que Laravel la maneje
        }
    }

    protected function assignUserRole(User $user, string $tipoUsuario)
    {
        try {
            // Si es el primer usuario, forzar rol de admin
            if(User::count() === 1) {
                $tipoUsuario = 'admin';
                $user->update(['tipo_usuario' => 'admin']);
            }

            $role = Role::firstOrCreate(['name' => $tipoUsuario]);
            $user->assignRole($role);

        } catch (\Exception $e) {
            \Log::error('Error asignando rol: '.$e->getMessage());
            throw $e;
        }
    }

    protected function createUserProfile(User $user, array $data)
    {
        try {
            $user->profile()->create([
                'nombre' => $data['nombre'],
                'apellido' => $data['apellido'],
                'area' => $data['tipo_usuario'] === 'empleado' ? 'Sin asignar' : null,
                'gmail' => null,
            ]);
        } catch (\Exception $e) {
            \Log::error('Error creando perfil: '.$e->getMessage());
            throw $e;
        }
    }

    public function username()
    {
        return 'RP';
    }

    protected function registered(Request $request, $user)
    {
        // Redirigir a home con mensaje de éxito
        return redirect($this->redirectPath())
            ->with('success', '¡Registro exitoso! Bienvenido '.$user->nombre);
    }

    // Sobrescribir el método register para mejor manejo de errores
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        $this->guard()->login($user);

        return $this->registered($request, $user)
            ?: redirect($this->redirectPath());
    }
}
