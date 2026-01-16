<?php

namespace App\Http\Controllers;

use App\Models\Movimiento;
use App\Models\Material;
use App\Models\TipoMovimiento;
use App\Models\User;
use App\Models\Stock;
use App\Models\Almacen;
use App\Models\Devolucion; // Mantenemos el modelo de devolución
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MovimientoController extends Controller
{
    /**
     * Display a listing of the resource.
     * INTEGRADO: Todos los filtros y lógica de permisos de Préstamos
     */
    public function index(Request $request): View
    {
//        $movimientos = Movimiento::with([
//            'devolucions',  // ¡ESTO ES CLAVE!
//            'tipoMovimiento',
//            'material',
//            'usuarioAsignado'
//        ])->latest()->paginate(20);

        $user = Auth::user();

        // Base query con eager loading
//        $query = Movimiento::with([
//            'material' => function($query) {
//                $query->withTrashed(); // Incluye materiales eliminados
//            },
//            'tipoMovimiento',
//            'usuarioRegistro',
//            'usuarioAsignado'
//        ]);
        $query = Movimiento::with([
            'devolucions',  // ¡Relación con minúscula!
            'material' => function($query) {
                $query->withTrashed();
            },
            'tipoMovimiento',
            'usuarioRegistro',
            'usuarioAsignado'
        ]);

        // ===== INTEGRACIÓN DE ROLES Y PERMISOS =====
        if ($user->hasRole('empleado')) {
            // Empleado ve solo sus movimientos asignados
            $query->where('usuario_asignado_id', $user->id);
        }
        // Admin y encargado ven todo (no se filtra)

        // ===== INTEGRACIÓN DE FILTROS AVANZADOS =====
        // Filtro por tipo
        if ($request->filled('tipo') && $request->tipo != '') {
            $query->whereHas('tipoMovimiento', function($q) use ($request) {
                $q->where('descripcion', 'LIKE', '%' . $request->tipo . '%');
            });
        }

        // Filtro por estado
        if ($request->filled('estado') && $request->estado != '') {
            $query->where('estado', $request->estado);
        }

        // Filtro por usuario asignado (búsqueda por nombre/apellido/RP)
        if ($request->filled('usuario') && $request->usuario != '') {
            $query->whereHas('usuarioAsignado', function($q) use ($request) {
                $q->where('nombre', 'LIKE', '%' . $request->usuario . '%')
                    ->orWhere('apellido', 'LIKE', '%' . $request->usuario . '%')
                    ->orWhere('RP', 'LIKE', '%' . $request->usuario . '%');
            });
        }

        // Filtro por material (como en Préstamos)
        if ($request->filled('material_id')) {
            $query->where('material_id', $request->material_id);
        }

        // Filtro por fechas (como en Préstamos)
        if ($request->filled('fecha_inicio')) {
            $query->whereDate('fecha', '>=', $request->fecha_inicio);
        }
        if ($request->filled('fecha_fin')) {
            $query->whereDate('fecha', '<=', $request->fecha_fin);
        }

        // Mostrar solo coincidencias (opción de checkbox)
        if ($request->filled('mostrar_coincidencias') && $request->filled('usuario')) {
            $searchTerm = $request->usuario;
            $query->whereHas('usuarioAsignado', function($q) use ($searchTerm) {
                $q->where('nombre', 'like', '%'.$searchTerm.'%')
                    ->orWhere('apellido', 'like', '%'.$searchTerm.'%');
            });
        }

        // Ordenar por fecha descendente y paginar
        $movimientos = $query->orderBy('fecha', 'desc')->paginate(10);

        // Datos para la vista
        return view('movimiento.index', [
            'movimientos' => $movimientos,
            'materials' => Material::orderBy('nombre')->get(),
            'tiposMovimiento' => TipoMovimiento::all(),
            'es_admin' => $user->hasRole('admin'),
            'es_encargado' => $user->hasRole('encargado'),
            'filters' => [
                'tipo' => $request->input('tipo', ''),
                'estado' => $request->input('estado', ''),
                'usuario' => $request->input('usuario', ''),
                'material_id' => $request->input('material_id'),
                'fecha_inicio' => $request->input('fecha_inicio'),
                'fecha_fin' => $request->input('fecha_fin'),
                'mostrar_coincidencias' => $request->input('mostrar_coincidencias', false)
            ]
        ]);

//        return view('movimiento.index', compact('movimientos'));
    }

    /**
     * Show the form for creating a new resource.
     * INTEGRADO: Validación de stock como en Préstamos
     */
    public function create(): View
    {
        // Excluir "Devolución" de los tipos disponibles para crear
        $tiposMovimiento = TipoMovimiento::where('descripcion', '!=', 'Devolución')->get();

        // Materiales con stock disponible (como en Préstamos)
        $materials = Material::whereHas('stocks', function($q) {
            $q->select(DB::raw('SUM(cantidad) as total'))
                ->havingRaw('SUM(cantidad) > 0');
        })->get();

//        $usuarios = User::all();

        $usuarios = User::where('tipo_usuario', 'empleado')
        ->orderBy('nombre')
        ->orderBy('apellido')
         ->get();

        return view('movimiento.create', compact('tiposMovimiento', 'usuarios', 'materials'));
    }

    /**
     * Store a newly created resource in storage.
     * INTEGRADO: Transacciones y validación de stock como en Préstamos
     */
//    public function store(Request $request): RedirectResponse
//    {
//        DB::beginTransaction();
//        try {
//            $validatedData = $this->validateMovimiento($request);
//            $tipoMovimiento = TipoMovimiento::findOrFail($validatedData['tipo_movimiento_id']);
//
//            // ===== INTEGRACIÓN DE VALIDACIÓN DE STOCK =====
//            if ($tipoMovimiento->descripcion === 'Salida Sin Retorno') {
//                $material = Material::with(['stocks'])->findOrFail($validatedData['material_id']);
//                $stockTotal = $material->stocks()->sum('cantidad');
//
//                if ($stockTotal < $validatedData['cantidad']) {
//                    return back()->withErrors([
//                        'cantidad' => 'No hay suficiente stock disponible. Stock total: '.$stockTotal
//                    ])->withInput();
//                }
//            }
//
//            // ===== INTEGRACIÓN DE LÓGICA DE DESCUENTO DE STOCK =====
//            if ($tipoMovimiento->descripcion === 'Salida Sin Retorno') {
//                $material = Material::with(['stocks'])->findOrFail($validatedData['material_id']);
//                $cantidadARestar = $validatedData['cantidad'];
//                $stocks = $material->stocks()->orderBy('created_at')->get();
//
//                foreach ($stocks as $stock) {
//                    if ($cantidadARestar <= 0) break;
//                    $resta = min($stock->cantidad, $cantidadARestar);
//                    $stock->decrement('cantidad', $resta);
//                    $cantidadARestar -= $resta;
//                }
//            }
//
//            // Crear movimiento
//            $movimiento = Movimiento::create([
//                'tipo_movimiento_id' => $validatedData['tipo_movimiento_id'],
//                'material_id' => $validatedData['material_id'],
//                'cantidad' => $validatedData['cantidad'],
//                'fecha' => $validatedData['fecha'],
//                'user_id' => auth()->id(),
//                'usuario_asignado_id' => $validatedData['usuario_asignado_id'] ?? null,
//                'notas' => $validatedData['notas'] ?? null,
//                'fecha_devolucion_estimada' => $validatedData['fecha_devolucion_estimada'] ?? null,
//                'es_sin_retorno' => $tipoMovimiento->descripcion === 'Salida Sin Retorno',
//                'estado' => 'activo',
//            ]);
//
//            DB::commit();
//
//            return redirect()->route('movimientos.index')
//                ->with('success', 'Movimiento registrado correctamente');
//
//        } catch (\Exception $e) {
//            DB::rollBack();
//            return back()->with('error', 'Error al registrar movimiento: ' . $e->getMessage())
//                ->withInput();
//        }
//    }
    public function store(Request $request): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $validatedData = $this->validateMovimiento($request);
            $tipoMovimiento = TipoMovimiento::findOrFail($validatedData['tipo_movimiento_id']);
            $tipoDesc = $tipoMovimiento->descripcion;

            $material = Material::with(['stocks'])->findOrFail($validatedData['material_id']);
            $stockTotal = $material->stocks->sum('cantidad');

            // ===== LÓGICA CONDICIONAL SEGÚN TIPO =====
            if ($tipoDesc === 'Salida Sin Retorno') {
                // Validar stock disponible
                if ($stockTotal < $validatedData['cantidad']) {
                    return back()->withErrors([
                        'cantidad' => 'No hay suficiente stock disponible. Stock total: '.$stockTotal
                    ])->withInput();
                }

                // Descontar PERMANENTEMENTE del stock
                $cantidadARestar = $validatedData['cantidad'];
                $stocks = $material->stocks()->orderBy('created_at')->get();

                foreach ($stocks as $stock) {
                    if ($cantidadARestar <= 0) break;
                    $resta = min($stock->cantidad, $cantidadARestar);
                    $stock->decrement('cantidad', $resta);
                    $cantidadARestar -= $resta;
                }

            } elseif ($tipoDesc === 'Resguardo') {
                // Validar stock disponible para RESGUARDO
                if ($stockTotal < $validatedData['cantidad']) {
                    return back()->withErrors([
                        'cantidad' => 'No hay suficiente stock para resguardo. Stock total: '.$stockTotal
                    ])->withInput();
                }

                // Aquí deberías crear un registro de "stock_reservado" o "stock_en_resguardo"
                // Por ahora solo validamos que haya stock
                // (Necesitarías una tabla adicional para stock reservado)

            } elseif ($tipoDesc === 'Préstamo') {
                // Para préstamos, no afectamos el stock
                // Solo validar que el material exista
            }

            // Crear movimiento
            $movimiento = Movimiento::create([
                'tipo_movimiento_id' => $validatedData['tipo_movimiento_id'],
                'material_id' => $validatedData['material_id'],
                'cantidad' => $validatedData['cantidad'],
                'fecha' => $validatedData['fecha'],
                'user_id' => auth()->id(), // Usuario que registra
//                'usuario_registro_id' => auth()->id(), // <-- ESTA ES LA LÍNEA IMPORTANTE
                'usuario_asignado_id' => $validatedData['usuario_asignado_id'] ?? null, // Usuario asignado
                'notas' => $validatedData['notas'] ?? null,
                'fecha_devolucion_estimada' => $validatedData['fecha_devolucion_estimada'] ?? null,
                'es_sin_retorno' => $tipoDesc === 'Salida Sin Retorno',
                'estado' => 'activo',
            ]);

            DB::commit();

            return redirect()->route('movimientos.index')
                ->with('success', 'Movimiento registrado correctamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al registrar movimiento: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     * INTEGRADO: Información de devoluciones como en Préstamos
     */
    public function show($id): View
    {
        $movimiento = Movimiento::with([
            'material' => function($query) {
                $query->withTrashed();
            },
            'tipoMovimiento',
            'usuarioRegistro',
            'usuarioAsignado',
            'devolucions' // Nueva relación
        ])->findOrFail($id);

        // ===== INTEGRACIÓN DE CÁLCULO DE DEVOLUCIONES =====
        $cantidad_devuelta = $movimiento->devolucions->sum('cantidad_devuelta');
        $pendiente = $movimiento->cantidad - $cantidad_devuelta;

        // Stock actual del material
        $stockTotal = $movimiento->material ?
            $movimiento->material->stocks->sum('cantidad') : 0;

        return view('movimiento.show', compact(
            'movimiento',
            'cantidad_devuelta',
            'pendiente',
            'stockTotal'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     * INTEGRADO: Materiales con stock disponible
     */
    public function edit($id): View
    {
        $movimiento = Movimiento::findOrFail($id);
        $tiposMovimiento = TipoMovimiento::where('descripcion', '!=', 'Devolución')->get();

        // Materiales con stock disponible (como en Préstamos)
        $materials = Material::whereHas('stocks', function($q) {
            $q->select(DB::raw('SUM(cantidad) as total'))
                ->havingRaw('SUM(cantidad) > 0');
        })->get();

        $usuarios = User::all();

        return view('movimiento.edit', compact('movimiento', 'tiposMovimiento', 'usuarios', 'materials'));
    }

    /**
     * Update the specified resource in storage.
     * INTEGRADO: Lógica de ajuste de stock como en Préstamos
     */
    public function update(Request $request, $id): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $movimiento = Movimiento::findOrFail($id);
            $validatedData = $this->validateMovimiento($request);
            $tipoMovimiento = TipoMovimiento::findOrFail($validatedData['tipo_movimiento_id']);

            // ===== INTEGRACIÓN DE AJUSTE DE STOCK COMO EN PRÉSTAMOS =====
            $diferencia = $validatedData['cantidad'] - $movimiento->cantidad;

            if ($tipoMovimiento->descripcion === 'Salida Sin Retorno') {
                $material = Material::with(['stocks'])->findOrFail($validatedData['material_id']);

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
                    $materialOriginal = Material::with(['stocks'])->findOrFail($movimiento->material_id);
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
            }

            // Actualizar movimiento
            $movimiento->update([
                'tipo_movimiento_id' => $validatedData['tipo_movimiento_id'],
                'material_id' => $validatedData['material_id'],
                'cantidad' => $validatedData['cantidad'],
                'fecha' => $validatedData['fecha'],
                'usuario_asignado_id' => $validatedData['usuario_asignado_id'] ?? null,
                'notas' => $validatedData['notas'] ?? null,
                'fecha_devolucion_estimada' => $validatedData['fecha_devolucion_estimada'] ?? null,
                'es_sin_retorno' => $tipoMovimiento->descripcion === 'Salida Sin Retorno',
                'estado' => $validatedData['estado'] ?? 'activo',
            ]);

            DB::commit();

            return redirect()->route('movimientos.index')
                ->with('success', 'Movimiento actualizado correctamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al actualizar movimiento: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     * INTEGRADO: Eliminación lógica y validación de devoluciones como en Préstamos
     */
    public function destroy($id): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $movimiento = Movimiento::withTrashed()->findOrFail($id);

            // Verificar si ya está eliminado
            if ($movimiento->trashed()) {
                throw new \Exception('Este movimiento ya fue eliminado anteriormente.');
            }

            // Verificar si tiene devoluciones asociadas
            if ($movimiento->devolucions()->exists()) {
                throw new \Exception('No se puede eliminar el movimiento porque tiene devoluciones asociadas.');
            }

            // Revertir stock si es Salida Sin Retorno
            if ($movimiento->tipoMovimiento->descripcion === 'Salida Sin Retorno') {
                $material = Material::with(['stocks'])->find($movimiento->material_id);
                if ($material) {
                    $stock = $material->stocks()->first();
                    if (!$stock) {
                        $stock = Stock::create([
                            'material_id' => $material->id,
                            'almacen_id' => 1,
                            'cantidad' => 0
                        ]);
                    }
                    $stock->increment('cantidad', $movimiento->cantidad);
                }
            }

            // Eliminación lógica (soft delete)
            $movimiento->delete();

            DB::commit();

            return redirect()->route('movimientos.index')
                ->with('success', 'Movimiento marcado como eliminado correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('movimientos.index')
                ->with('error', $e->getMessage());
        }
    }

    /**
     * ===== INTEGRACIÓN COMPLETA DE DEVOLUCIONES =====
     * Métodos para manejar devoluciones dentro del sistema de Movimientos
     */

    /**
     * Obtener datos para devolución (AJAX)
     * INTEGRADO: De PrestamoController
     */
    public function datosDevolucion($id)
    {
        $movimiento = Movimiento::with(['material', 'usuarioAsignado', 'devolucions'])
            ->findOrFail($id);

        $cantidad_devuelta = $movimiento->devolucions->sum('cantidad_devuelta');
        $pendiente = $movimiento->cantidad - $cantidad_devuelta;
        $stockTotal = $movimiento->material ?
            $movimiento->material->stocks->sum('cantidad') : 0;

        return response()->json([
            'movimiento_id' => $movimiento->id,
            'material_id' => $movimiento->material_id,
            'material_nombre' => $movimiento->material->nombre ?? 'Sin material',
            'cantidad' => $movimiento->cantidad,
            'usuario_asignado_id' => $movimiento->usuario_asignado_id,
            'usuario_nombre' => ($movimiento->usuarioAsignado->nombre ?? 'Sin usuario') . ' ' .
                ($movimiento->usuarioAsignado->apellido ?? ''),
            'descripcion' => $movimiento->notas,
            'cantidad_pendiente' => $pendiente,
            'stock_actual' => $stockTotal
        ]);
    }

    /**
     * Procesar devolución
     * INTEGRADO: De PrestamoController
     */
//    public function procesarDevolucion(Request $request)
//    {
//        $validatedData = $request->validate([
//            'movimiento_id' => 'required|exists:movimientos,id',
//            'cantidad_devuelta' => 'required|numeric|min:0.01',
//            'fecha_devolucion' => 'required|date|before_or_equal:today',
//            'descripcion_estado' => 'required|string|max:500',
//            'almacen_id' => 'required|exists:almacens,id'
//        ]);
//
//        DB::beginTransaction();
//        try {
//            $movimiento = Movimiento::with(['material', 'devoluciones'])->findOrFail($validatedData['movimiento_id']);
//            $totalDevuelto = $movimiento->devoluciones->sum('cantidad_devuelta');
//            $pendiente = $movimiento->cantidad - $totalDevuelto;
//
//            if ($validatedData['cantidad_devuelta'] > $pendiente) {
//                throw new \Exception("La cantidad a devolver ({$validatedData['cantidad_devuelta']}) excede lo pendiente ($pendiente)");
//            }
//
//            // Crear devolución
//            $devolucion = Devolucion::create([
//                'movimiento_id' => $movimiento->id,
//                'cantidad_devuelta' => $validatedData['cantidad_devuelta'],
//                'fecha_devolucion' => $validatedData['fecha_devolucion'],
//                'descripcion_estado' => $validatedData['descripcion_estado']
//            ]);
//
//            // Actualizar stock
//            if ($movimiento->material) {
//                Stock::firstOrCreate(
//                    ['material_id' => $movimiento->material_id, 'almacen_id' => $validatedData['almacen_id']],
//                    ['cantidad' => 0]
//                )->increment('cantidad', $validatedData['cantidad_devuelta']);
//            }
//
//            // Si se devolvió todo, marcar movimiento como devuelto
//            if (($totalDevuelto + $validatedData['cantidad_devuelta']) >= $movimiento->cantidad) {
//                $movimiento->update(['estado' => 'devuelto']);
//            }
//
//            DB::commit();
//
//            return redirect()->route('movimientos.index')
//                ->with('success', 'Devolución registrada correctamente.');
//
//        } catch (\Exception $e) {
//            DB::rollBack();
//            return back()->withInput()
//                ->with('error', 'Error al registrar la devolución: ' . $e->getMessage());
//        }
//    }

// MovimientoController.php
//    public function procesarDevolucion($id)
//    {
//        // DEBUG: Ver qué datos llegan
//        \Log::info('=== INICIANDO PROCESAR DEVOLUCIÓN ===');
//        \Log::info('Movimiento ID: ' . $id);
//        \Log::info('Datos recibidos:', request()->all());
//
//        try {
//            // Validar datos del formulario
//            $validated = request()->validate([
//                'cantidad_devuelta' => 'required|numeric|min:0.01',
//                'fecha_devolucion' => 'required|date',
//                'almacen_id' => 'required|exists:almacens,id',
//                'descripcion_estado' => 'required|string|min:3',
//            ]);
//
//            \Log::info('Datos validados:', $validated);
//
//            // Buscar el movimiento
//            $movimiento = Movimiento::with('devoluciones')->findOrFail($id);
//            \Log::info('Movimiento encontrado:', ['id' => $movimiento->id, 'cantidad' => $movimiento->cantidad]);
//
//            // Calcular cantidad ya devuelta
//            $cantidad_devuelta_total = $movimiento->devoluciones->sum('cantidad_devuelta');
//            $pendiente = $movimiento->cantidad - $cantidad_devuelta_total;
//
//            \Log::info('Cálculos:', [
//                'devuelto_total' => $cantidad_devuelta_total,
//                'pendiente' => $pendiente,
//                'a_devolver' => $validated['cantidad_devuelta']
//            ]);
//
//            // Validar que no exceda lo pendiente
//            if ($validated['cantidad_devuelta'] > $pendiente) {
//                \Log::error('Error: Cantidad excede pendiente');
//                return back()->with('error', 'Error: La cantidad a devolver (' . $validated['cantidad_devuelta'] . ') excede la cantidad pendiente (' . $pendiente . ')');
//            }
//
//            // Crear la devolución
//            $devolucion = Devolucion::create([
//                'movimiento_id' => $id,
//                'prestamo_id' => $movimiento->prestamo_id ?? null,
//                'fecha_devolucion' => $validated['fecha_devolucion'],
//                'cantidad_devuelta' => $validated['cantidad_devuelta'],
//                'almacen_id' => $validated['almacen_id'],
//                'descripcion_estado' => $validated['descripcion_estado'],
//            ]);
//
//            \Log::info('Devolución creada:', ['id' => $devolucion->id]);
//
//            // Actualizar estado si se devolvió completamente
//            $nuevo_total_devuelto = $cantidad_devuelta_total + $validated['cantidad_devuelta'];
//
//            if ($nuevo_total_devuelto >= $movimiento->cantidad) {
//                $movimiento->estado = 'devuelto';
//                $movimiento->save();
//                \Log::info('Movimiento marcado como devuelto');
//            }
//
//            \Log::info('=== DEVOLUCIÓN PROCESADA EXITOSAMENTE ===');
//
//            return redirect()->route('movimientos.index')
//                ->with('success', 'Devolución registrada exitosamente. Se devolvieron ' . $validated['cantidad_devuelta'] . ' unidades.');
//
//        } catch (\Illuminate\Validation\ValidationException $e) {
//            \Log::error('Error de validación:', ['errors' => $e->errors()]);
//            return back()->withErrors($e->errors())->withInput();
//
//        } catch (\Exception $e) {
//            \Log::error('Error general:', [
//                'message' => $e->getMessage(),
//                'file' => $e->getFile(),
//                'line' => $e->getLine(),
//                'trace' => $e->getTraceAsString()
//            ]);
//
//            return back()->with('error', 'Error al procesar la devolución: ' . $e->getMessage())
//                ->withInput();
//        }
//    }
//
//    /**
//     * Convertir préstamo a resguardo (opcional)
//     */
    public function convertirAResguardo($id): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $movimiento = Movimiento::findOrFail($id);
            $tipoResguardo = TipoMovimiento::where('descripcion', 'Resguardo')->first();

            if (!$tipoResguardo) {
                return back()->with('error', 'Tipo de movimiento "Resguardo" no encontrado');
            }

            // Cambiar tipo a Resguardo
            $movimiento->update([
                'tipo_movimiento_id' => $tipoResguardo->id,
                'es_sin_retorno' => false,
            ]);

            DB::commit();

            return redirect()->route('movimientos.index')
                ->with('success', 'Movimiento convertido a resguardo correctamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al convertir: ' . $e->getMessage());
        }
    }
    /**
     * Procesar devolución
     * INTEGRADO: De PrestamoController
     */
//    public function procesarDevolucion(Request $request, $id)
//    {
//        \Log::info('=== INICIANDO PROCESAR DEVOLUCIÓN ===');
//        \Log::info('Movimiento ID: ' . $id);
//        \Log::info('Datos recibidos:', $request->all());
//
//        DB::beginTransaction();
//        try {
//            // 1. Validar datos del formulario
//            $validated = $request->validate([
//                'cantidad_devuelta' => 'required|numeric|min:0.01',
//                'fecha_devolucion' => 'required|date',
//                'almacen_id' => 'required|exists:almacens,id',
//                'descripcion_estado' => 'required|string|min:3',
//            ]);
//
//            \Log::info('Datos validados:', $validated);
//
//            // 2. Buscar el movimiento con relaciones necesarias
//            $movimiento = Movimiento::with(['devoluciones', 'tipoMovimiento', 'material'])->findOrFail($id);
//
//            \Log::info('Movimiento encontrado:', [
//                'id' => $movimiento->id,
//                'cantidad' => $movimiento->cantidad,
//                'tipo' => $movimiento->tipoMovimiento->descripcion ?? 'N/A',
//                'estado_actual' => $movimiento->estado
//            ]);
//
//            // 3. Verificar que el movimiento pueda ser devuelto
//            if ($movimiento->estado === 'devuelto') {
//                throw new \Exception('Este movimiento ya está completamente devuelto.');
//            }
//
//            if ($movimiento->estado === 'perdido') {
//                throw new \Exception('No se pueden registrar devoluciones para movimientos marcados como perdidos.');
//            }
//
//            // 4. Verificar tipo de movimiento
//            $es_prestamo = $movimiento->tipoMovimiento && $movimiento->tipoMovimiento->descripcion == 'Préstamo';
//            $es_resguardo = $movimiento->tipoMovimiento && $movimiento->tipoMovimiento->descripcion == 'Resguardo';
//
//            if (!$es_prestamo && !$es_resguardo) {
//                throw new \Exception('Solo se pueden devolver préstamos y resguardos.');
//            }
//
//            // 5. Calcular cantidad ya devuelta
//            $cantidad_devuelta_total = $movimiento->devoluciones->sum('cantidad_devuelta');
//            $pendiente = $movimiento->cantidad - $cantidad_devuelta_total;
//
//            \Log::info('Cálculos de devolución:', [
//                'devuelto_total' => $cantidad_devuelta_total,
//                'pendiente' => $pendiente,
//                'a_devolver' => $validated['cantidad_devuelta']
//            ]);
//
//            // 6. Validar que no exceda lo pendiente
//            if ($validated['cantidad_devuelta'] > $pendiente) {
//                throw new \Exception('La cantidad a devolver (' . $validated['cantidad_devuelta'] . ') excede la cantidad pendiente (' . $pendiente . ')');
//            }
//
//            // 7. Crear la devolución usando modelo directo
//            $devolucion = new Devolucion();
//            $devolucion->movimiento_id = $id;
//            $devolucion->prestamo_id = $movimiento->prestamo_id ?? null;
//            $devolucion->fecha_devolucion = $validated['fecha_devolucion'];
//            $devolucion->cantidad_devuelta = $validated['cantidad_devuelta'];
//            $devolucion->almacen_id = $validated['almacen_id'];
//            $devolucion->descripcion_estado = $validated['descripcion_estado'];
//
//            if (!$devolucion->save()) {
//                throw new \Exception('Error al guardar la devolución en la base de datos.');
//            }
//
//            \Log::info('Devolución creada exitosamente:', ['id' => $devolucion->id]);
//
//            // 8. Actualizar stock del material
//            if ($movimiento->material) {
//                // Buscar o crear stock para el material en el almacén especificado
//                $stock = Stock::firstOrCreate(
//                    [
//                        'material_id' => $movimiento->material_id,
//                        'almacen_id' => $validated['almacen_id']
//                    ],
//                    ['cantidad' => 0]
//                );
//
//                $stock->increment('cantidad', $validated['cantidad_devuelta']);
//                \Log::info('Stock actualizado:', [
//                    'material_id' => $movimiento->material_id,
//                    'almacen_id' => $validated['almacen_id'],
//                    'cantidad_agregada' => $validated['cantidad_devuelta'],
//                    'nuevo_stock' => $stock->cantidad
//                ]);
//            }
//
//            // 9. Actualizar estado del movimiento si se devolvió completamente
//            $nuevo_total_devuelto = $cantidad_devuelta_total + $validated['cantidad_devuelta'];
//
//            if ($nuevo_total_devuelto >= $movimiento->cantidad) {
//                $movimiento->estado = 'devuelto';
//                $movimiento->save();
//                \Log::info('Movimiento marcado como completamente devuelto');
//            }
//
//            // 10. Confirmar transacción
//            DB::commit();
//
//            \Log::info('=== DEVOLUCIÓN PROCESADA EXITOSAMENTE ===');
//
//            return redirect()->route('movimientos.index')
//                ->with('success', 'Devolución registrada exitosamente. Se devolvieron ' .
//                    $validated['cantidad_devuelta'] . ' unidades al almacén.');
//
//        } catch (\Illuminate\Validation\ValidationException $e) {
//            DB::rollBack();
//            \Log::error('Error de validación:', ['errors' => $e->errors()]);
//            return back()->withErrors($e->errors())->withInput();
//
//        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
//            DB::rollBack();
//            \Log::error('Movimiento no encontrado:', ['id' => $id]);
//            return back()->with('error', 'Movimiento no encontrado.')->withInput();
//
//        } catch (\Exception $e) {
//            DB::rollBack();
//            \Log::error('Error general en procesarDevolucion:', [
//                'message' => $e->getMessage(),
//                'file' => $e->getFile(),
//                'line' => $e->getLine(),
//                'trace' => $e->getTraceAsString()
//            ]);
//
//            return back()->with('error', 'Error al procesar la devolución: ' . $e->getMessage())
//                ->withInput();
//        }
//    }

//    public function procesarDevolucion(Request $request, $id)
//    {
//        DB::beginTransaction();
//        try {
//            // Validar datos
//            $request->validate([
//                'cantidad_devuelta' => 'required|numeric|min:0.01',
//                'fecha_devolucion' => 'required|date',
//                'descripcion_estado' => 'required|string|max:500',
//                'almacen_id' => 'required|exists:almacens,id'
//            ]);
//
//            // Obtener el movimiento
//            $movimiento = Movimiento::findOrFail($id);
//
//            // Calcular cantidad pendiente
//            $cantidad_devuelta_total = $movimiento->devoluciones->sum('cantidad_devuelta');
//            $cantidad_pendiente = $movimiento->cantidad - $cantidad_devuelta_total;
//
//            // Validar que haya cantidad pendiente para devolver
//            if ($request->cantidad_devuelta > $cantidad_pendiente) {
//                return response()->json([
//                    'success' => false,
//                    'message' => 'La cantidad a devolver no puede ser mayor a la pendiente: ' . $cantidad_pendiente
//                ], 422);
//            }
//
//            // Crear la devolución
////            $devolucion = Devolucion::create([
////                'movimiento_id' => $movimiento->id,
////                'cantidad_devuelta' => $request->cantidad_devuelta,
////                'fecha_devolucion' => $request->fecha_devolucion,
////                'descripcion_estado' => $request->descripcion_estado,
////                'almacen_id' => $request->almacen_id,
////                'prestamo_id' => null // Si no es del sistema antiguo
////            ]);
//// Crear la devolución (sin prestamo_id si no existe en fillable)
//            $devolucions = Devolucion::create([
//                'movimiento_id' => $movimiento->id,
//                'cantidad_devuelta' => $request->cantidad_devuelta,
//                'fecha_devolucion' => $request->fecha_devolucion,
//                'descripcion_estado' => $request->descripcion_estado,
//                'almacen_id' => $request->almacen_id,
//                // 'prestamo_id' no es necesario si no está en fillable
//            ]);
//
//            // ACTUALIZAR STOCK EN EL ALMACÉN
//            $stock = Stock::firstOrCreate(
//                [
//                    'material_id' => $movimiento->material_id,
//                    'almacen_id' => $request->almacen_id
//                ],
//                ['cantidad' => 0]
//            );
//
//            $stock->increment('cantidad', $request->cantidad_devuelta);
//
//            // Verificar si el movimiento está completamente devuelto
//            $nueva_cantidad_devuelta_total = $cantidad_devuelta_total + $request->cantidad_devuelta;
//
//            if ($nueva_cantidad_devuelta_total >= $movimiento->cantidad) {
//                $movimiento->update([
//                    'estado' => 'devuelto',
//                    'fecha_devolucion_real' => now()
//                ]);
//            }
//
//            DB::commit();
//
//            return response()->json([
//                'success' => true,
//                'message' => 'Devolución registrada correctamente',
//                'redirect' => route('movimientos.index')
//            ]);
//
//        } catch (\Exception $e) {
//            DB::rollBack();
//            return response()->json([
//                'success' => false,
//                'message' => 'Error al registrar devolución: ' . $e->getMessage()
//            ], 500);
//        }
//    }

    public function procesarDevolucion(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            // Validar datos
            $request->validate([
                'cantidad_devuelta' => 'required|numeric|min:0.01',
                'fecha_devolucion' => 'required|date',
                'descripcion_estado' => 'required|string|max:500',
                'almacen_id' => 'required|exists:almacens,id'
            ]);

            // Obtener el movimiento CON la relación correcta
            $movimiento = Movimiento::with('devolucions')->findOrFail($id); // <- 'devolucions' no 'devoluciones'

            // Calcular cantidad pendiente
            $cantidad_devuelta_total = $movimiento->devolucions->sum('cantidad_devuelta'); // <- 'devolucions'
            $cantidad_pendiente = $movimiento->cantidad - $cantidad_devuelta_total;

            // Validar que haya cantidad pendiente para devolver
            if ($request->cantidad_devuelta > $cantidad_pendiente) {
                return response()->json([
                    'success' => false,
                    'message' => 'La cantidad a devolver no puede ser mayor a la pendiente: ' . $cantidad_pendiente
                ], 422);
            }

            // Crear la devolución
            $devolucion = Devolucion::create([
                'movimiento_id' => $movimiento->id,
                'cantidad_devuelta' => $request->cantidad_devuelta,
                'fecha_devolucion' => $request->fecha_devolucion,
                'descripcion_estado' => $request->descripcion_estado,
                'almacen_id' => $request->almacen_id,
            ]);

            // ACTUALIZAR STOCK EN EL ALMACÉN
            $stock = Stock::firstOrCreate(
                [
                    'material_id' => $movimiento->material_id,
                    'almacen_id' => $request->almacen_id
                ],
                ['cantidad' => 0]
            );

            $stock->increment('cantidad', $request->cantidad_devuelta);

            // Verificar si el movimiento está completamente devuelto
            $nueva_cantidad_devuelta_total = $cantidad_devuelta_total + $request->cantidad_devuelta;

            if ($nueva_cantidad_devuelta_total >= $movimiento->cantidad) {
                $movimiento->update([
                    'estado' => 'devuelto',
                    'fecha_devolucion_real' => now()
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Devolución registrada correctamente',
                'redirect' => route('movimientos.index')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar devolución: ' . $e->getMessage()
            ], 500);
        }
    }
    /**
     * MÉTODOS PRIVADOS DE AYUDA
     */

    private function validateMovimiento(Request $request): array
    {
        return $request->validate([
            'tipo_movimiento_id' => 'required|exists:tipo_movimientos,id',
            'material_id' => 'required|exists:materials,id',
            'cantidad' => 'required|numeric|min:0.01',
            'fecha' => 'required|date',
            'usuario_asignado_id' => 'nullable|exists:users,id',
            'notas' => 'nullable|string',
            'fecha_devolucion_estimada' => 'nullable|date|after:fecha',
            'estado' => 'nullable|in:activo,devuelto,perdido',
        ]);
    }

    /**
     * ===== MÉTODOS PARA MIGRACIÓN DE DATOS EXISTENTES =====
     */

    /**
     * Migrar préstamos existentes a movimientos
     */
    public function migrarPrestamos()
    {
        DB::beginTransaction();
        try {
            $prestamos = \App\Models\Prestamo::with(['material', 'user'])->get();
            $tipoPrestamo = TipoMovimiento::where('descripcion', 'Préstamo')->first();

            if (!$tipoPrestamo) {
                throw new \Exception('Tipo de movimiento "Préstamo" no encontrado');
            }

            $contador = 0;
            foreach ($prestamos as $prestamo) {
                // Verificar si ya existe un movimiento para este préstamo
                $existe = Movimiento::where('tipo_movimiento_id', $tipoPrestamo->id)
                    ->where('material_id', $prestamo->material_id)
                    ->where('usuario_asignado_id', $prestamo->user_id)
                    ->where('cantidad', $prestamo->cantidad_prestada)
                    ->whereDate('fecha', $prestamo->fecha_prestamo)
                    ->exists();

                if (!$existe) {
                    Movimiento::create([
                        'tipo_movimiento_id' => $tipoPrestamo->id,
                        'material_id' => $prestamo->material_id,
                        'cantidad' => $prestamo->cantidad_prestada,
                        'fecha' => $prestamo->fecha_prestamo,
                        'user_id' => $prestamo->user_id, // Usuario que creó el préstamo
                        'usuario_asignado_id' => $prestamo->user_id, // Usuario asignado
                        'notas' => $prestamo->descripcion . ' (Migrado desde sistema de préstamos)',
                        'es_sin_retorno' => false,
                        'estado' => 'activo',
                    ]);
                    $contador++;
                }
            }

            DB::commit();

            return redirect()->route('movimientos.index')
                ->with('success', "Migración completada. Se migraron $contador préstamos a movimientos.");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('movimientos.index')
                ->with('error', 'Error en migración: ' . $e->getMessage());
        }
    }

    /**
     * Dashboard de movimientos
     */
    public function dashboard()
    {
        $estadisticas = [
            'prestamos_activos' => Movimiento::whereHas('tipoMovimiento', function($q) {
                $q->where('descripcion', 'Préstamo');
            })->where('estado', 'activo')->count(),

            'resguardos_activos' => Movimiento::whereHas('tipoMovimiento', function($q) {
                $q->where('descripcion', 'Resguardo');
            })->where('estado', 'activo')->count(),

            'salidas_sin_retorno' => Movimiento::whereHas('tipoMovimiento', function($q) {
                $q->where('descripcion', 'Salida Sin Retorno');
            })->count(),

            'total_movimientos' => Movimiento::count(),

            'movimientos_hoy' => Movimiento::whereDate('fecha', today())->count(),
        ];

        return view('movimiento.dashboard', compact('estadisticas'));
    }
}
