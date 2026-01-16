<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use PHPUnit\TextUI\XmlConfiguration\Validator;

class UserController extends Controller
{
    const PER_PAGE = 10; // Items por página

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = User::query();

        // Búsqueda por nombre o email
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(function($q) use ($searchTerm) {
                $q->where('nombre', 'like', "%$searchTerm%")
                    ->orWhere('RP', 'like', "%$searchTerm%");
            });
        }

        $users = $query->paginate(self::PER_PAGE);
        $pageOffset = ($request->input('page', 1) - 1) * $users->perPage();

        return view('user.index', compact('users', 'pageOffset'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('user.create', ['user' => new User()]);
    }

    /**
     * Store a newly created resource in storage.
     */
//    public function store(UserRequest $request): RedirectResponse
//    {
//        $data = $request->validated();
//
//        // Asegurarse de que la contraseña esté presente y hasheada
//        if (!isset($data['password'])) {
//            $data['password'] = Hash::make('password123'); // Contraseña por defecto
//        } else {
//            $data['password'] = Hash::make($data['password']);
//        }
//
//        User::create($data);
//
//        return redirect()->route('users.index')
//            ->with('success', 'Usuario creado exitosamente.');
//    }
//    public function store(Request $request)
//    {
//        User::create([
//            'nombre' => $request->nombre,
//            'apellido' => $request->apellido,
//            'rp' => $request->rp,
//            'tipo_usuario' => $request->tipo_usuario,
//            'password' => Hash::make($request->password), // ENCRIPTAR AQUÍ
//        ]);
//
//        return redirect()->route('usuarios.index')->with('success', 'Usuario creado correctamente.');
//    }

//    public function store(Request $request)
//    {
//        // Validación de campos
//        $validated = $request->validate([
//            'nombre' => 'required|string|max:255',
//            'apellido' => 'required|string|max:255',
//            'RP' => 'required|string|max:50|unique:users,RP', // Asegura que el RP sea único
//                'tipo_usuario' => ['required', Rule::in(['admin', 'encargado', 'empleado'])], // Ajusta según tus tipos
//            'password' => 'required|string|min:8|confirmed',
//        ]);
//
//        // Crear usuario con contraseña hasheada
//        User::create([
//            'nombre' => $validated['nombre'],
//            'apellido' => $validated['apellido'],
//            'RP' => strtoupper(trim($validated['RP'])), // Normaliza el RP
//            'tipo_usuario' => $validated['tipo_usuario'],
//            'password' => Hash::make($validated['password']),
//        ]);
//
//        return redirect()->route('users.index')->with('success', 'Usuario creado correctamente.');
//    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'RP' => 'required|string|max:50|unique:users,RP',
            'tipo_usuario' => 'required|in:admin,encargado,empleado',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = new User();
        $user->nombre = $validated['nombre'];
        $user->apellido = $validated['apellido'];
        $user->RP = $validated['RP'];
        $user->tipo_usuario = $validated['tipo_usuario'];
        $user->password = bcrypt($validated['password']);
        $user->save();

        // 🔐 Asignar el rol automáticamente según tipo_usuario
        if (in_array($user->tipo_usuario, ['admin', 'encargado', 'empleado'])) {
            $user->assignRole($user->tipo_usuario);
        }

        return redirect()->route('users.index')->with('success', 'Usuario creado y rol asignado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): View
    {
        return view('user.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user): View
    {
        return view('user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
//    public function update(UserRequest $request, User $user): RedirectResponse
//    {
//        $data = $request->validated();
//
//        // Solo actualizar la contraseña si se proporcionó una nueva
//        if (isset($data['password'])) {
//            $data['password'] = Hash::make($data['password']);
//        } else {
//            unset($data['password']);
//        }
//
//        $user->update($data);
//
//        return redirect()->route('users.index')
//            ->with('success', 'Usuario actualizado correctamente');
//    }
//    public function update(Request $request, $id)
//    {
//        $user = User::findOrFail($id);
//
//        $user->nombre = $request->nombre;
//        $user->apellido = $request->apellido;
//        $user->rp = $request->rp;
//        $user->tipo_usuario = $request->tipo_usuario;
//
//        if ($request->filled('password')) {
//            $user->password = Hash::make($request->password); // SOLO SI VIENE UNA NUEVA CONTRASEÑA
//        }
//
//        $user->save();
//
//        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado correctamente.');
//    }


    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'RP' => [
                'required',
                'string',
                'max:50',
                Rule::unique('users')->ignore($user->id) // Permite mantener el mismo RP
            ],
            'tipo_usuario' => ['required', Rule::in(['admin', 'encargado', 'empleado'])],
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->nombre = $validated['nombre'];
        $user->apellido = $validated['apellido'];
        $user->RP = strtoupper(trim($validated['RP']));
        $user->tipo_usuario = $validated['tipo_usuario'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('users.index')->with('success', 'Usuario actualizado correctamente.');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        // Validar dependencias antes de eliminar
        if ($user->ingresos()->exists() || $user->prestamos()->exists()) {
            return redirect()->back()
                ->with('error', 'No se puede eliminar: usuario tiene registros asociados');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'Usuario eliminado correctamente');
    }

    // arcivo csv

    public function showImportForm()
    {
        return view('user.import');
    }

    public function downloadTemplate()
    {
        $headers = ['nombre', 'apellido', 'RP', 'tipo_usuario', 'password'];
        $filename = "plantilla_importacion_usuarios.csv";

        $callback = function () use ($headers) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $headers);
            fclose($handle);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    }

    public function processImport(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
            'has_header' => 'nullable|boolean'
        ]);

        $file = $request->file('csv_file');
        $hasHeader = $request->input('has_header', true);

        $csvData = array_map('str_getcsv', file($file->getRealPath()));

        if ($hasHeader) {
            $header = array_map('strtolower', array_shift($csvData));
        } else {
            $header = ['nombre', 'apellido', 'RP', 'tipo_usuario', 'password'];
        }

        $importedCount = 0;
        $errors = [];
        $duplicados = [];

        DB::beginTransaction();

        try {
            foreach ($csvData as $index => $row) {
                if (empty(array_filter($row))) continue;

                if (count($row) != count($header)) {
                    $errors[$index] = ['Número de columnas no coincide'];
                    continue;
                }

                $rowData = array_combine($header, $row);

                // Verificar si ya existe por RP
                $usuarioExistente = User::where('RP', $rowData['RP'])->first();
                if ($usuarioExistente) {
                    $duplicados[$index] = $rowData['RP'];
                    continue;
                }

                $validator = Validator::make($rowData, [
                    'nombre' => 'required|string|max:255',
                    'apellido' => 'required|string|max:255',
                    'RP' => 'required|string|max:255',
                    'tipo_usuario' => 'required|in:admin,encargado,empleado',
                    'password' => 'required|string|min:6',
                ]);

                if ($validator->fails()) {
                    $errors[$index] = $validator->errors()->all();
                    continue;
                }

                // Crear usuario
                User::create([
                    'nombre' => $rowData['nombre'],
                    'apellido' => $rowData['apellido'],
                    'RP' => $rowData['RP'],
                    'tipo_usuario' => $rowData['tipo_usuario'],
                    'password' => Hash::make($rowData['password']),
                ]);

                $importedCount++;
            }

            DB::commit();

            return redirect()->route('users.index')->with([
                'success' => "Se importaron $importedCount usuarios correctamente.",
                'warning' => count($duplicados) > 0 ? "Se omitieron ".count($duplicados)." registros duplicados." : null,
                'import_errors' => $errors,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error durante la importación: ' . $e->getMessage())->withInput();
        }
    }

}
