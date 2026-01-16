<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Prestamo;
use App\Models\User;
use App\Models\Devolucion;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PrestamoController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->hasRole('admin') || $user->hasRole('encargado')) {
            // Admin y encargado ven todo
            $prestamos = Prestamo::with(['material' => function($query) {
                $query->withTrashed(); // Incluye materiales eliminados
            }])
                ->with(['user', 'devolucions'])
                ->orderBy('fecha_prestamo', 'desc')
                ->paginate(10);
        } else {
            // Empleado ve solo los suyos
            $prestamos = Prestamo::with(['material' => function($query) {
                $query->withTrashed(); // Incluye materiales eliminados
            }])
                ->with(['user', 'devolucions'])
                ->where('user_id', $user->id)
                ->orderBy('fecha_prestamo', 'desc')
                ->paginate(10);
        }

        return view('prestamo.index', [
            'prestamos' => $prestamos,
            'es_admin' => $user->hasRole('admin'),
            'es_encargado' => $user->hasRole('encargado')
        ]);
    }
//    public function index(Request $request)
//    {
//        $user = Auth::user();
//
//
//        if ($user->hasRole('admin') || $user->hasRole('encargado')) {
//            // Admin y encargado ven todo
//            $prestamos = Prestamo::with(['user', 'material'])
//                ->orderBy('fecha_prestamo', 'desc')
//                ->paginate(10);
//        } else {
//            // Empleado ve solo los suyos
//            $prestamos = Prestamo::with(['user', 'material'])
//                ->where('user_id', $user->id)
//                ->orderBy('fecha_prestamo', 'desc')
//                ->paginate(10);
//        }
//        $prestamos = Prestamo::withTrashed()
//            ->with(['material', 'user', 'devolucions'])
//            ->orderBy('fecha_prestamo', 'desc')
//            ->paginate(10);
//
//        return view('prestamo.index', [
//            'prestamos' => $prestamos,
//            'es_admin' => $user->hasRole('admin'),
//            'es_encargado' => $user->hasRole('encargado')
//        ]);
//
//    }


//    public function index(Request $request)
//    {
//        $user = Auth::user();
//
//        if ($user->hasRole('admin') || $user->hasRole('encargado')) {
//            // Admin ve todo
//            $prestamos = Prestamo::with(['user', 'material'])
//                ->orderBy('fecha_prestamo', 'desc')
//                ->paginate(10);
//        } else {
//            // Empleado ve solo los suyos
//            $prestamos = Prestamo::with(['user', 'material'])
//                ->where('user_id', $user->id)
//                ->orderBy('fecha_prestamo', 'desc')
//                ->paginate(10);
//        }
//
//        return view('prestamo.index', [
//            'prestamos' => $prestamos,
//            'es_admin' => $user->hasRole('admin')
//        ]);
//    }
//    public function index(Request $request)
//    {
//        $user = Auth::user();
//        abort_unless($user, 403, 'Debes iniciar sesión para acceder.');
//
//        $query = Prestamo::with(['user', 'material', 'devolucions']);
//
//        // SOLUCIÓN: Cambiamos la condición para que sea más explícita
//        if ($user->hasRole('empleado')) {  // Solo empleados ven sus préstamos
//            $query->where('user_id', $user->id);
//        }
//
//        // Resto del código permanece igual...
//        // Aplicar filtros de búsqueda
//        if ($request->filled('persona')) {
//            $searchTerm = $request->input('persona');
//            $query->whereHas('user', function($q) use ($searchTerm) {
//                $q->where('nombre', 'like', '%'.$searchTerm.'%')
//                    ->orWhere('apellido', 'like', '%'.$searchTerm.'%');
//            });
//        }
//
//        // Filtro por material si está presente
//        if ($request->filled('material_id')) {
//            $query->where('material_id', $request->material_id);
//        }
//
//        // Filtro por fechas
//        if ($request->filled('fecha_inicio')) {
//            $query->whereDate('fecha_prestamo', '>=', $request->fecha_inicio);
//        }
//
//        if ($request->filled('fecha_fin')) {
//            $query->whereDate('fecha_prestamo', '<=', $request->fecha_fin);
//        }
//
//        // Si se seleccionó "Mostrar solo coincidencias"
//        if ($request->filled('mostrar_coincidencias') && $request->filled('persona')) {
//            $searchTerm = $request->input('persona');
//            $query->whereHas('user', function($q) use ($searchTerm) {
//                $q->where('nombre', 'like', '%'.$searchTerm.'%')
//                    ->orWhere('apellido', 'like', '%'.$searchTerm.'%');
//            });
//        }
//
//        // Ordenar por fecha de préstamo descendente y paginar
//        $prestamos = $query->orderByDesc('fecha_prestamo')->paginate(10);
//
//        return view('prestamo.index', [
//            'prestamos' => $prestamos,
//            'materials' => Material::orderBy('nombre')->get(),
//            'filters' => [
//                'search' => $request->input('persona', ''),
//                'material_id' => $request->input('material_id'),
//                'fecha_inicio' => $request->input('fecha_inicio'),
//                'fecha_fin' => $request->input('fecha_fin'),
//                'mostrar_coincidencias' => $request->input('mostrar_coincidencias', false)
//            ]
//        ]);
//    }


//    public function index(Request $request)
//    {
//        $user = Auth::user();
//        abort_unless($user, 403, 'Debes iniciar sesión para acceder.');
//
//        // Consulta base con relaciones - ahora todos los usuarios ven todos los préstamos
//        $query = Prestamo::with(['user', 'material', 'devolucions']);
//
//        // Aplicar filtros de búsqueda
//        if ($request->filled('persona')) {
//            $searchTerm = $request->input('persona');
//            $query->whereHas('user', function($q) use ($searchTerm) {
//                $q->where('nombre', 'like', '%'.$searchTerm.'%')
//                    ->orWhere('apellido', 'like', '%'.$searchTerm.'%');
//            });
//        }
//
//        // Filtro por material si está presente
//        if ($request->filled('material_id')) {
//            $query->where('material_id', $request->material_id);
//        }
//
//        // Filtro por fechas
//        if ($request->filled('fecha_inicio')) {
//            $query->whereDate('fecha_prestamo', '>=', $request->fecha_inicio);
//        }
//
//        if ($request->filled('fecha_fin')) {
//            $query->whereDate('fecha_prestamo', '<=', $request->fecha_fin);
//        }
//
//        // Si se seleccionó "Mostrar solo coincidencias"
//        if ($request->filled('mostrar_coincidencias') && $request->filled('persona')) {
//            $searchTerm = $request->input('persona');
//            $query->whereHas('user', function($q) use ($searchTerm) {
//                $q->where('nombre', 'like', '%'.$searchTerm.'%')
//                    ->orWhere('apellido', 'like', '%'.$searchTerm.'%');
//            });
//        }
//
//        // Ordenar por fecha de préstamo descendente y paginar
//        $prestamos = $query->orderByDesc('fecha_prestamo')->paginate(10);
//
//        return view('prestamo.index', [
//            'prestamos' => $prestamos,
//            'materials' => Material::orderBy('nombre')->get(),
//            'filters' => [
//                'search' => $request->input('persona', ''),
//                'material_id' => $request->input('material_id'),
//                'fecha_inicio' => $request->input('fecha_inicio'),
//                'fecha_fin' => $request->input('fecha_fin'),
//                'mostrar_coincidencias' => $request->input('mostrar_coincidencias', false)
//            ]
//        ]);
//    }


//
//    public function index(Request $request)
//    {
//        $user = auth()->user();
//
//        if (!$user) {
//            abort(403, 'Debes iniciar sesión para acceder a esta sección.');
//        }
//
//        // Inicializar la consulta base según el rol
//        if ($user->hasAnyRole(['admin', 'encargado'])) {
//            // Admin y encargado ven todos los préstamos
//            $query = Prestamo::with(['user', 'material', 'devolucions']);
//
//            // Aplicar filtros solo para estos roles
//            $this->applyFilters($query, $request);
//        } else {
//            // Empleados solo ven sus propios préstamos
//            $query = $user->prestamos()->with(['material', 'devolucions']);
//        }
//
//        // Ordenar y paginar
//        $prestamos = $query->latest('fecha_prestamo')
//            ->paginate(10);
//
//        return view('prestamo.index', [
//            'prestamos' => $prestamos,
//            'materials' => $this->getMaterials($user),
//            'search' => $request->search ?? '',
//            'material_id' => $request->material_id ?? null,
//            'fecha_inicio' => $request->fecha_inicio ?? '',
//            'fecha_fin' => $request->fecha_fin ?? ''
//        ]);
//    }
//
//    protected function applyFilters($query, Request $request)
//    {
//        // Búsqueda por usuario
//        if ($request->filled('search')) {
//            $query->whereHas('user', function ($q) use ($request) {
//                $q->where('nombre', 'like', "%{$request->search}%")
//                    ->orWhere('apellido', 'like', "%{$request->search}%")
//                    ->orWhere('RP', 'like', "%{$request->search}%");
//            });
//        }
//
//        // Filtro por material
//        if ($request->filled('material_id')) {
//            $query->where('material_id', $request->material_id);
//        }
//
//        // Filtro por fechas
//        if ($request->filled('fecha_inicio')) {
//            $query->where('fecha_prestamo', '>=', $request->fecha_inicio);
//        }
//        if ($request->filled('fecha_fin')) {
//            $query->where('fecha_prestamo', '<=', $request->fecha_fin);
//        }
//    }
//
//    protected function getMaterials($user)
//    {
//        return $user->hasAnyRole(['admin', 'encargado'])
//            ? Material::all()
//            : collect();
//    }


//    public function index(Request $request)
//    {
//        $user = auth()->user();
//
//        // Verificar autenticación
//        if (!$user) {
//            abort(403, 'Debes iniciar sesión para acceder a esta sección.');
//        }
//
//        // Depuración de roles (puedes comentar esto después de verificar)
//        \Log::info('Usuario accediendo:', [
//            'id' => $user->id,
//            'name' => $user->name,
//            'roles' => $user->getRoleNames()->toArray()
//        ]);
//
//        $query = Prestamo::query();
//
//        // Lógica para administradores y encargados
//        if ($user->hasRole('admin') || $user->hasRole('encargado')) {
//            $query->with(['material', 'user', 'devolucions']);
//
//            // Filtros de búsqueda
//            if ($request->has('search') && !empty($request->search)) {
//                $search = $request->search;
//                $query->whereHas('user', function($q) use ($search) {
//                    $q->where('name', 'like', "%$search%")
//                        ->orWhere('last_name', 'like', "%$search%")
//                        ->orWhere('employee_id', 'like', "%$search%");
//                });
//            }
//
//            // Filtro por material
//            if ($request->has('material_id') && !empty($request->material_id)) {
//                $query->where('material_id', $request->material_id);
//            }
//
//            // Filtros por fecha
//            if ($request->has('fecha_inicio') && !empty($request->fecha_inicio)) {
//                $query->where('fecha_prestamo', '>=', $request->fecha_inicio);
//            }
//            if ($request->has('fecha_fin') && !empty($request->fecha_fin)) {
//                $query->where('fecha_prestamo', '<=', $request->fecha_fin);
//            }
//
//        }
//        // Lógica para empleados
//        elseif ($user->hasRole('empleado')) {
//            $query->with(['material', 'user', 'devolucions'])
//                ->where('user_id', $user->id);
//        }
//        // Si no tiene ningún rol válido
//        else {
//            abort(403, 'No tienes permisos para acceder a esta sección.');
//        }
//
//        // Paginación y ordenamiento
//        $prestamos = $query->orderBy('fecha_prestamo', 'desc')
//            ->paginate(10);
//
//        // Materiales solo para admin/encargado
//        $materials = ($user->hasRole('admin') || $user->hasRole('encargado'))
//            ? Material::all()
//            : collect();
//
//        return view('prestamo.index', [
//            'prestamos' => $prestamos,
//            'materials' => $materials,
//            'search' => $request->search ?? '',
//            'material_id' => $request->material_id ?? '',
//            'fecha_inicio' => $request->fecha_inicio ?? '',
//            'fecha_fin' => $request->fecha_fin ?? ''
//        ]);
//    }
/////////////////////////////////////////////////////////////////////////////////////
/// este si funciona para todos lo usuarios
//    public function index(Request $request)
//    {
//        // Temporalmente permitir acceso a todos los usuarios autenticados
//        if (!auth()->check()) {
//            abort(403, 'Debes iniciar sesión para acceder a esta sección.');
//        }
//
//        $query = Prestamo::with(['material', 'user', 'devolucions']);
//
//        // Filtros comunes
//        if ($request->has('search') && !empty($request->search)) {
//            $search = $request->search;
//            $query->whereHas('user', function($q) use ($search) {
//                $q->where('name', 'like', "%$search%")
//                    ->orWhere('last_name', 'like', "%$search%")
//                    ->orWhere('employee_id', 'like', "%$search%");
//            });
//        }
//
//        if ($request->has('material_id') && !empty($request->material_id)) {
//            $query->where('material_id', $request->material_id);
//        }
//
//        if ($request->has('fecha_inicio') && !empty($request->fecha_inicio)) {
//            $query->where('fecha_prestamo', '>=', $request->fecha_inicio);
//        }
//        if ($request->has('fecha_fin') && !empty($request->fecha_fin)) {
//            $query->where('fecha_prestamo', '<=', $request->fecha_fin);
//        }
//
//        $prestamos = $query->orderBy('fecha_prestamo', 'desc')->paginate(10);
//        $materials = Material::all();
//
//        return view('prestamo.index', [
//            'prestamos' => $prestamos,
//            'materials' => $materials,
//            'search' => $request->search ?? '',
//            'material_id' => $request->material_id ?? '',
//            'fecha_inicio' => $request->fecha_inicio ?? '',
//            'fecha_fin' => $request->fecha_fin ?? ''
//        ]);
//    }


//el que funciona es el de abajo

//    public function index(Request $request)
//    {
//        $user = auth()->user();
//
//        $query = Prestamo::query()->with(['material', 'user', 'devolucions']);
//
//        if ($user->hasRole('empleado')) {
//            $query->where('user_id', $user->id);
//        } else {
//            if ($request->filled('search')) {
//                $search = $request->search;
//                $query->whereHas('user', function ($q) use ($search) {
//                    $q->where('nombre', 'like', "%$search%")
//                        ->orWhere('apellido', 'like', "%$search%")
//                        ->orWhere('RP', 'like', "%$search%");
//                });
//            }
//
//            if ($request->filled('material_id')) {
//                $query->where('material_id', $request->material_id);
//            }
//
//            if ($request->filled('fecha_inicio')) {
//                $query->where('fecha_prestamo', '>=', $request->fecha_inicio);
//            }
//            if ($request->filled('fecha_fin')) {
//                $query->where('fecha_prestamo', '<=', $request->fecha_fin);
//            }
//        }
//
//        $prestamos = $query->orderBy('fecha_prestamo', 'desc')->paginate(10);
//
//        return view('prestamo.index', [
//            'prestamos' => $prestamos,
//            'materials' => $user->hasAnyRole(['encargado', 'administrador']) ? Material::all() : collect(),
//            'search' => $request->search ?? '',
//            'material_id' => $request->material_id ?? '',
//            'fecha_inicio' => $request->fecha_inicio ?? '',
//            'fecha_fin' => $request->fecha_fin ?? ''
//        ]);
//    }



//vewrsion funcional del dia 9/01/26, este metodo hace que funcione de forma adecuada el sistema de la funcion de prestamo, si en dado caso dejase de funcionar el nuevo metodo puedes volver a este y no afectara la funcionalidad de las tablas
//    public function create()
//    {
//        $prestamo = new Prestamo();
//        $prestamo->fecha_prestamo = now()->toDateString();
//
//        $materials = Material::whereHas('stocks', function($q) {
//            $q->select(DB::raw('SUM(cantidad) as total'))
//                ->havingRaw('SUM(cantidad) > 0');
//        })->get();
//
//        $users = User::all();
//
//        return view('prestamo.create', compact('prestamo', 'materials', 'users'));
//    }

    public function create()
    {
        // Verificar si el usuario quiere usar el sistema nuevo de movimientos
        if (request()->has('usar_movimientos') || !request()->ajax()) {
            // Redirigir al formulario unificado de movimientos
            return redirect()->route('movimientos.create')
                ->with('info', 'Seleccione "Préstamo" como tipo de movimiento')
                ->with('preselected_type', 'Préstamo');
        }

        // Mantener el sistema antiguo para compatibilidad
        $prestamo = new Prestamo();
        $prestamo->fecha_prestamo = now()->toDateString();

        $materials = Material::whereHas('stocks', function($q) {
            $q->select(DB::raw('SUM(cantidad) as total'))
                ->havingRaw('SUM(cantidad) > 0');
        })->get();

        $users = User::all();

        return view('prestamo.create', compact('prestamo', 'materials', 'users'));
    }

    //nuevo metdodo si deja de funcionar la tabla de movimientos o no funciona es necesario eliminar o modificar estemetodo, esto es para crear un nuevo mi¿ovimiento al momeno de crear el prestamo
    /**
     * Crear un movimiento desde el sistema de préstamos
     * Mantiene compatibilidad con URLs antiguas
     */
    public function createMovimiento(Request $request)
    {
        // Redirigir al formulario unificado de movimientos
        $params = [];

        // Preseleccionar tipo si viene de préstamo
        if ($request->has('tipo')) {
            $params['tipo'] = $request->tipo;
        } else {
            $params['tipo'] = 'Préstamo';
        }

        // Preseleccionar material si viene
        if ($request->has('material_id')) {
            $params['material_id'] = $request->material_id;
        }

        // Preseleccionar usuario si viene
        if ($request->has('user_id')) {
            $params['usuario_asignado_id'] = $request->user_id;
        }

        return redirect()->route('movimientos.create', $params)
            ->with('info', 'Ahora se utilizan Movimientos unificados. Seleccione el tipo de movimiento.')
            ->with('from_prestamos', true);
    }

//vewrsion funcional del dia 9/01/26, este metodo hace que funcione de forma adecuada el sistema de la funcion de prestamo, si en dado caso dejase de funcionar el nuevo metodo puedes volver a este y no afectara la funcionalidad de las tablas

//    public function store(Request $request)
//    {
//        $validatedData = $request->validate([
//            'fecha_prestamo' => 'required|date',
//            'cantidad_prestada' => 'required|numeric|min:0.01',
//            'material_id' => 'required|exists:materials,id',
//            'user_id' => 'required|exists:users,id',
//            'descripcion' => 'required|string|max:255',
//        ]);
//
//        DB::beginTransaction();
//        try {
//            $material = Material::with(['stocks'])->findOrFail($validatedData['material_id']);
//            $stockTotal = $material->stocks->sum('cantidad');
//
//            if ($stockTotal < $validatedData['cantidad_prestada']) {
//                return back()->withErrors([
//                    'cantidad_prestada' => 'No hay suficiente stock disponible. Stock total: '.$stockTotal
//                ])->withInput();
//            }
//
//            $cantidadARestar = $validatedData['cantidad_prestada'];
//            $stocks = $material->stocks()->orderBy('created_at')->get();
//
//            foreach ($stocks as $stock) {
//                if ($cantidadARestar <= 0) break;
//                $resta = min($stock->cantidad, $cantidadARestar);
//                $stock->decrement('cantidad', $resta);
//                $cantidadARestar -= $resta;
//            }
//
//            Prestamo::create($validatedData);
//
//            DB::commit();
//
//            return redirect()->route('prestamos.index')
//                ->with('success', 'Préstamo creado correctamente.');
//
//        } catch (\Exception $e) {
//            DB::rollBack();
//            return back()->withInput()
//                ->with('error', 'Error al crear el préstamo: ' . $e->getMessage());
//        }
//    }

    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'fecha_prestamo' => 'required|date',
            'cantidad_prestada' => 'required|numeric|min:0.01',
            'material_id' => 'required|exists:materials,id',
            'user_id' => 'required|exists:users,id',
            'descripcion' => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $material = Material::with(['stocks'])->findOrFail($validatedData['material_id']);
            $stockTotal = $material->stocks()->sum('cantidad');

            if ($stockTotal < $validatedData['cantidad_prestada']) {
                return back()->withErrors([
                    'cantidad_prestada' => 'No hay suficiente stock disponible. Stock total: '.$stockTotal
                ])->withInput();
            }

            $cantidadARestar = $validatedData['cantidad_prestada'];
            $stocks = $material->stocks()->orderBy('created_at')->get();

            foreach ($stocks as $stock) {
                if ($cantidadARestar <= 0) break;
                $resta = min($stock->cantidad, $cantidadARestar);
                $stock->decrement('cantidad', $resta);
                $cantidadARestar -= $resta;
            }

            // 1. Crear en sistema antiguo (PRESTAMOS)
            $prestamos = Prestamo::create($validatedData);

            // 2. Crear también en sistema nuevo (MOVIMIENTOS) para compatibilidad
            try {
                // Buscar tipo "Préstamo" en la tabla de tipos de movimiento
                $tipoPrestamo = \App\Models\TipoMovimiento::where('descripcion', 'Préstamo')->first();

                if ($tipoPrestamo) {
                    \App\Models\Movimiento::create([
                        'tipo_movimiento_id' => $tipoPrestamo->id,
                        'material_id' => $validatedData['material_id'],
                        'cantidad' => $validatedData['cantidad_prestada'],
                        'fecha' => $validatedData['fecha_prestamo'],
                        'user_id' => auth()->id(),
                        'usuario_asignado_id' => $validatedData['user_id'],
                        'notas' => $validatedData['descripcion'] . ' (Creado desde sistema de préstamos)',
                        'estado' => 'activo',
                        'es_sin_retorno' => false,
                    ]);
                }
            } catch (\Exception $e) {
                // Si falla la creación en movimientos, continuar con préstamo
                \Log::warning('No se pudo crear movimiento automático: ' . $e->getMessage());
            }

            DB::commit();

            return redirect()->route('prestamos.index')
                ->with('success', 'Préstamo creado correctamente. ' .
                    ($tipoPrestamo ? 'También se registró en Movimientos.' : ''));

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Error al crear el préstamo: ' . $e->getMessage());
        }
    }


    public function show($id)
    {
        $prestamo = Prestamo::with(['material', 'user', 'devolucions'])->findOrFail($id);
        $cantidad_devuelta = $prestamo->devolucions->sum('cantidad_devuelta');
        $pendiente = $prestamo->cantidad_prestada - $cantidad_devuelta;

        return view('prestamo.show', compact('prestamo', 'cantidad_devuelta', 'pendiente'));
    }

    public function edit(Prestamo $prestamo)
    {
        $materials = Material::whereHas('stocks', function($q) {
            $q->select(DB::raw('SUM(cantidad) as total'))
                ->havingRaw('SUM(cantidad) > 0');
        })->get();

        $users = User::all();

        return view('prestamo.edit', compact('prestamo', 'materials', 'users'));
    }

    public function update(Request $request, Prestamo $prestamo)
    {
        $request->validate([
            'material_id' => 'required|exists:materials,id',
            'fecha_prestamo' => 'required|date',
            'cantidad_prestada' => 'required|numeric|min:0.01',
            'user_id' => 'required|exists:users,id',
            'descripcion' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $diferencia = $request->cantidad_prestada - $prestamo->cantidad_prestada;
            $material = Material::with(['stocks'])->findOrFail($request->material_id);

            if ($diferencia > 0) {
                $stockTotal = $material->stocks->sum('cantidad');
                if ($stockTotal < $diferencia) {
                    throw new \Exception('No hay suficiente stock disponible. Stock actual: '.$stockTotal);
                }

                $cantidadARestar = $diferencia;
                $stocks = $material->stocks()->orderBy('created_at')->get();

                foreach ($stocks as $stock) {
                    if ($cantidadARestar <= 0) break;
                    $resta = min($stock->cantidad, $cantidadARestar);
                    $stock->decrement('cantidad', $resta);
                    $cantidadARestar -= $resta;
                }
            } elseif ($diferencia < 0) {
                $materialOriginal = Material::with(['stocks'])->findOrFail($prestamo->material_id);
                $stock = $materialOriginal->stocks()->first();
                if (!$stock) {
                    $stock = Stock::create([
                        'material_id' => $materialOriginal->id,
                        'almacen_id' => 1,
                        'cantidad' => 0
                    ]);
                }
                $stock->increment('cantidad', abs($diferencia));
            }

            $prestamo->update($request->all());

            DB::commit();

            return redirect()->route('prestamos.index')
                ->with('success', 'Préstamo actualizado correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Error al actualizar: ' . $e->getMessage());
        }
    }

//    public function destroy($id)
//    {
//        DB::beginTransaction();
//        try {
//            $prestamo = Prestamo::findOrFail($id);
//
//            if ($prestamo->devolucions()->exists()) {
//                throw new \Exception('No se puede eliminar el préstamo porque tiene devoluciones asociadas.');
//            }
//
//            $material = Material::with(['stocks'])->find($prestamo->material_id);
//            if ($material) {
//                $stock = $material->stocks()->first();
//                if (!$stock) {
//                    $stock = Stock::create([
//                        'material_id' => $material->id,
//                        'almacen_id' => 1,
//                        'cantidad' => 0
//                    ]);
//                }
//                $stock->increment('cantidad', $prestamo->cantidad_prestada);
//            }
//
//            $prestamo->delete();
//
//            DB::commit();
//
//            return redirect()->route('prestamos.index')
//                ->with('success', 'Préstamo eliminado correctamente.');
//
//        } catch (\Exception $e) {
//            DB::rollBack();
//            return redirect()->route('prestamos.index')
//                ->with('error', $e->getMessage());
//        }
//    }
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            // Buscar préstamo incluyendo registros eliminados lógicamente
            $prestamo = Prestamo::withTrashed()->findOrFail($id);

            // Verificar si el préstamo ya está eliminado
            if ($prestamo->trashed()) {
                throw new \Exception('Este préstamo ya fue eliminado anteriormente.');
            }

            if ($prestamo->devolucions()->exists()) {
                throw new \Exception('No se puede eliminar el préstamo porque tiene devoluciones asociadas.');
            }

            $material = Material::with(['stocks'])->find($prestamo->material_id);
            if ($material) {
                $stock = $material->stocks()->first();
                if (!$stock) {
                    $stock = Stock::create([
                        'material_id' => $material->id,
                        'almacen_id' => 1,
                        'cantidad' => 0
                    ]);
                }
                $stock->increment('cantidad', $prestamo->cantidad_prestada);
            }

            // Eliminación lógica (soft delete)
            $prestamo->delete();

            DB::commit();

            return redirect()->route('prestamos.index')
                ->with('success', 'Préstamo marcado como eliminado correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('prestamos.index')
                ->with('error', $e->getMessage());
        }
    }
    public function datosDevolucion($id)
    {
        $prestamo = Prestamo::with(['material', 'user', 'devolucions'])->findOrFail($id);
        $cantidad_devuelta = $prestamo->devolucions->sum('cantidad_devuelta');
        $pendiente = $prestamo->cantidad_prestada - $cantidad_devuelta;
        $stockTotal = $prestamo->material ? $prestamo->material->stocks->sum('cantidad') : 0;

        return response()->json([
            'prestamo_id' => $prestamo->id,
            'material_id' => $prestamo->material_id,
            'material_nombre' => $prestamo->material->nombre ?? 'Sin material',
            'cantidad_prestada' => $prestamo->cantidad_prestada,
            'user_id' => $prestamo->user_id,
            'user_nombre' => ($prestamo->user->nombre ?? 'Sin user') . ' ' . ($prestamo->user->apellido ?? ''),
            'descripcion' => $prestamo->descripcion,
            'cantidad_pendiente' => $pendiente,
            'stock_actual' => $stockTotal
        ]);
    }

    public function procesarDevolucion(Request $request)
    {
        $validatedData = $request->validate([
            'prestamo_id' => 'required|exists:prestamos,id',
            'cantidad_devuelta' => 'required|numeric|min:0.01',
            'fecha_devolucion' => 'required|date|before_or_equal:today',
            'descripcion_estado' => 'required|string|max:500',
            'almacen_id' => 'required|exists:almacens,id'
        ]);

        DB::beginTransaction();
        try {
            $prestamo = Prestamo::with(['material', 'devolucions'])->findOrFail($validatedData['prestamo_id']);
            $totalDevuelto = $prestamo->devolucions->sum('cantidad_devuelta');
            $pendiente = $prestamo->cantidad_prestada - $totalDevuelto;

            if ($validatedData['cantidad_devuelta'] > $pendiente) {
                throw new \Exception("La cantidad a devolver ({$validatedData['cantidad_devuelta']}) excede lo pendiente ($pendiente)");
            }

            Devolucion::create([
                'prestamo_id' => $prestamo->id,
                'cantidad_devuelta' => $validatedData['cantidad_devuelta'],
                'fecha_devolucion' => $validatedData['fecha_devolucion'],
                'descripcion_estado' => $validatedData['descripcion_estado']
            ]);

            Stock::firstOrCreate(
                ['material_id' => $prestamo->material_id, 'almacen_id' => $validatedData['almacen_id']],
                ['cantidad' => 0]
            )->increment('cantidad', $validatedData['cantidad_devuelta']);

            DB::commit();

            return redirect()->route('prestamos.index')
                ->with('success', 'Devolución registrada correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Error al registrar la devolución: ' . $e->getMessage());
        }
    }
}
