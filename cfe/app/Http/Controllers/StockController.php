<?php

namespace App\Http\Controllers;

//use App\Models\Stock;
//use App\Models\Material;
//use App\Models\Almacen;
//use Illuminate\Http\RedirectResponse;
//use Illuminate\Http\Request;
//use Illuminate\View\View;
//use Illuminate\Support\Facades\DB;

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\Material;
use App\Models\Almacen;
use App\Models\Ingreso; // ← SOLO ESTA LÍNEA
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Stock::with(['material', 'almacen']);

        // Búsqueda por material
        if ($request->has('material') && $request->material != '') {
            $materialBuscado = $request->material;

            if ($request->has('mostrar_coincidencias') && $request->mostrar_coincidencias) {
                // Coincidencias exactas
                $query->whereHas('material', function ($q) use ($materialBuscado) {
                    $q->where('nombre', 'LIKE', '%' . $materialBuscado . '%');
                });
            } else {
                // Búsqueda más amplia
                $query->whereHas('material', function ($q) use ($materialBuscado) {
                    $q->where('nombre', 'LIKE', '%' . $materialBuscado . '%')
                        ->orWhere('codigo', 'LIKE', '%' . $materialBuscado . '%');
                });
            }
        }

        $stocks = $query->paginate(15);

        return view('stock.index', compact('stocks'))
            ->with('i', ($request->input('page', 1) - 1) * $stocks->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): RedirectResponse
    {
        return redirect()->route('ingresos.create')
            ->with('info', 'Para agregar stock, debe registrar un ingreso primero.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        return redirect()->route('ingresos.create')
            ->with('info', 'Para agregar stock, debe registrar un ingreso.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $stock = Stock::with(['material', 'almacen'])->findOrFail($id);
        return view('stock.show', compact('stock'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): RedirectResponse
    {
        return redirect()->route('ingresos.index')
            ->with('info', 'Para modificar stock, edite el ingreso correspondiente.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        return redirect()->route('ingresos.index')
            ->with('info', 'Los ajustes de stock se realizan a través de ingresos.');
    }

    /**
     * Remove the specified resource from storage.
     */
//    public function destroy($id): RedirectResponse
//    {
//        DB::beginTransaction();
//        try {
//            $stock = Stock::findOrFail($id);
//
//            // Soft delete en lugar de eliminación permanente
//            $stock->delete();
//
//            DB::commit();
//            return redirect()->route('stocks.index')
//                ->with('success', 'Stock movido a la papelera correctamente.');
//        } catch (\Exception $e) {
//            DB::rollBack();
//            return redirect()->route('stocks.index')
//                ->with('error', 'Error al eliminar el stock: ' . $e->getMessage());
//        }
//    }
    /**
     * Remove the specified resource from storage.
     */
//    public function destroy($id): RedirectResponse
//    {
//        DB::beginTransaction();
//        try {
//            $stock = Stock::with(['material'])->findOrFail($id);
//
//            // 1. Buscar ingresos relacionados con este material
//            $ingresosRelacionados = \App\Models\Ingreso::where('material_id', $stock->material_id)->get();
//
//            // 2. Eliminar (soft delete) los ingresos relacionados
//            foreach ($ingresosRelacionados as $ingreso) {
//                $ingreso->update([
//                    'nota' => ($ingreso->nota ?? '') . "\n🗑️ ELIMINADO junto con Stock ID: " . $stock->id . " - " . now()->format('d/m/Y H:i')
//                ]);
//                $ingreso->delete(); // Soft delete
//            }
//
//            // 3. Registrar un ingreso de ajuste
//            \App\Models\Ingreso::create([
//                'cantidad_ingresada' => -$stock->cantidad, // Negativo para indicar salida
//                'fecha' => now()->toDateString(),
//                'material_id' => $stock->material_id,
//                'user_id' => auth()->id() ?? 1,
//                'nota' => '🗑️ ELIMINACIÓN A PAPELERA - Stock ID: ' . $stock->id .
//                    ' | Cantidad retirada: ' . $stock->cantidad .
//                    ' | Material: ' . ($stock->material->nombre ?? 'N/A')
//            ]);
//
//            // 4. Soft delete del stock
//            $stock->delete();
//
//            DB::commit();
//            return redirect()->route('stocks.index')
//                ->with('success', [
//                    'title' => '✅ Stock a Papelera',
//                    'message' => 'Stock ID ' . $stock->id . ' movido a papelera.',
//                    'details' => 'Se eliminaron ' . $ingresosRelacionados->count() . ' ingreso(s) relacionados.',
//                    'link' => route('ingresos.index'),
//                    'link_text' => 'Ver Ingresos'
//                ]);
//
//        } catch (\Exception $e) {
//            DB::rollBack();
//            return redirect()->route('stocks.index')
//                ->with('error', '❌ Error al eliminar el stock: ' . $e->getMessage());
//        }
//    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $stock = Stock::with(['material'])->findOrFail($id);

            // 1. Buscar y eliminar (soft delete) ingresos relacionados
            $ingresosRelacionados = \App\Models\Ingreso::where('material_id', $stock->material_id)->get();
            $ingresosEliminados = 0;

            foreach ($ingresosRelacionados as $ingreso) {
                $ingreso->delete(); // Soft delete
                $ingresosEliminados++;
            }

            // 2. Registrar un ingreso de ajuste por la eliminación
            \App\Models\Ingreso::create([
                'cantidad_ingresada' => -$stock->cantidad, // Negativo para indicar salida
                'fecha' => now()->toDateString(),
                'material_id' => $stock->material_id,
                'user_id' => auth()->id() ?? 1,
                'nota' => '🗑️ ELIMINACIÓN A PAPELERA - Stock ID: ' . $stock->id .
                    ' | Cantidad: ' . $stock->cantidad .
                    ' | Material: ' . ($stock->material->nombre ?? 'N/A')
            ]);

            // 3. Soft delete del stock
            $stock->delete();

            DB::commit();
            return redirect()->route('stocks.index')
                ->with('success', [
                    'title' => '✅ Stock a Papelera',
                    'message' => 'Stock ID ' . $stock->id . ' movido a papelera.',
                    'details' => 'Se eliminaron ' . $ingresosEliminados . ' ingreso(s) relacionados.',
                    'link' => route('ingresos.index'),
                    'link_text' => 'Ver Ingresos'
                ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error al eliminar stock: ' . $e->getMessage());
            return redirect()->route('stocks.index')
                ->with('error', '❌ Error al eliminar el stock: ' . $e->getMessage());
        }
    }
    /**
     * ============================================
     * MÉTODOS PARA LA PAPELERA DE RECICLAJE
     * ============================================
     */

    /**
     * Mostrar la papelera de reciclaje de stocks.
     */
    public function trash(Request $request): View
    {
        // Consulta base: solo stocks eliminados (soft delete)
        $query = Stock::onlyTrashed()->with(['material', 'almacen']);

        // Filtro por búsqueda de material (opcional)
        if ($request->has('material') && $request->material != '') {
            $materialBuscado = $request->material;
            $query->whereHas('material', function($q) use ($materialBuscado) {
                $q->where('nombre', 'LIKE', '%' . $materialBuscado . '%')
                    ->orWhere('codigo', 'LIKE', '%' . $materialBuscado . '%');
            });
        }

        // Filtro por almacén (opcional)
        if ($request->has('almacen') && $request->almacen != '') {
            $almacenBuscado = $request->almacen;
            $query->whereHas('almacen', function($q) use ($almacenBuscado) {
                $q->where('nombre', 'LIKE', '%' . $almacenBuscado . '%');
            });
        }

        // Ordenar por fecha de eliminación (más reciente primero)
        $query->orderBy('deleted_at', 'desc');

        // Paginación
        $stocks = $query->paginate(15)->withQueryString();

        // Retornar vista
        return view('stock.trash', compact('stocks'))
            ->with('i', ($request->input('page', 1) - 1) * $stocks->perPage());
    }

    /**
     * Restaurar un stock desde la papelera.
     */
//    public function restore($id): RedirectResponse
//    {
//        DB::beginTransaction();
//        try {
//            $stock = Stock::onlyTrashed()->findOrFail($id);
//            $stock->restore();
//
//            DB::commit();
//            return redirect()->route('stocks.trash')
//                ->with('success', 'Stock restaurado correctamente.');
//        } catch (\Exception $e) {
//            DB::rollBack();
//            return redirect()->route('stocks.trash')
//                ->with('error', 'Error al restaurar el stock: ' . $e->getMessage());
//        }
//    }
//    public function restore($id): RedirectResponse
//    {
//        DB::beginTransaction();
//        try {
//            $stock = Stock::onlyTrashed()->findOrFail($id);
//
//            // 1. Restaurar el stock
//            $stock->restore();
//
//            // 2. Buscar si hay ingresos relacionados con este material
//            $ingresosRelacionados = \App\Models\Ingreso::where('material_id', $stock->material_id)->get();
//
//            if ($ingresosRelacionados->isNotEmpty()) {
//                // 3. Crear un nuevo ingreso por la cantidad restaurada
//                \App\Models\Ingreso::create([
//                    'cantidad_ingresada' => $stock->cantidad,
//                    'fecha' => now(),
//                    'material_id' => $stock->material_id,
//                    'user_id' => auth()->id() ?? 1, // Usuario actual o default
//                    'nota' => 'Restauración automática desde papelera de stock - Stock ID: ' . $stock->id
//                ]);
//
//                // 4. Opcional: Actualizar los ingresos existentes si es necesario
//                foreach ($ingresosRelacionados as $ingreso) {
//                    // Puedes agregar lógica aquí si necesitas modificar ingresos existentes
//                    // Por ejemplo, agregar una nota
//                    $ingreso->update([
//                        'notas' => ($ingreso->notas ?? '') . "\nStock relacionado restaurado el " . now()->format('d/m/Y')
//                    ]);
//                }
//            }
//
//            DB::commit();
//
//            // Redirigir al índice de stocks en lugar de la papelera
//            return redirect()->route('stocks.index')
//                ->with('success', 'Stock restaurado correctamente. Se ha creado un nuevo ingreso automáticamente.');
//
//        } catch (\Exception $e) {
//            DB::rollBack();
//            return redirect()->route('stocks.trash')
//                ->with('error', 'Error al restaurar el stock: ' . $e->getMessage());
//        }
//    }
    /**
     * Restaurar un stock desde la papelera (CON INGRESOS RELACIONADOS).
     */
//    public function restore($id): RedirectResponse
//    {
//        DB::beginTransaction();
//        try {
//            // 1. Obtener stock eliminado con relaciones
//            $stock = Stock::onlyTrashed()
//                ->with(['material', 'material.ingresos' => function($q) {
//                    $q->onlyTrashed()->orderBy('created_at', 'desc');
//                }])
//                ->findOrFail($id);
//
//            // 2. Verificar si hay stock activo existente del mismo material
//            $stockExistente = Stock::where('material_id', $stock->material_id)->first();
//
//            $cantidadRestaurada = $stock->cantidad;
//            $ingresosRestaurados = 0;
//            $nuevoIngresoId = null;
//
//            if ($stockExistente) {
//                // CASO A: Ya existe stock activo - Sumar cantidades
//                $stockExistente->cantidad += $stock->cantidad;
//                $stockExistente->save();
//
//                // Eliminar permanentemente el stock de la papelera (no lo necesitamos)
//                $stock->forceDelete();
//
//                $accion = "SUMADO a stock existente";
//            } else {
//                // CASO B: No hay stock activo - Restaurar el stock
//                $stock->restore();
//                $accion = "RESTAURADO como nuevo stock";
//            }
//
//            // 3. Restaurar ingresos relacionados eliminados
//            if ($stock->material && $stock->material->ingresos) {
//                foreach ($stock->material->ingresos as $ingreso) {
//                    if ($ingreso->trashed()) {
//                        $ingreso->restore();
//                        $ingresosRestaurados++;
//
//                        // Actualizar nota del ingreso
//                        $ingreso->update([
//                            'nota' => ($ingreso->nota ?? '') . "\n🔄 RESTAURADO junto con Stock ID: " . $stock->id . " - " . now()->format('d/m/Y H:i')
//                        ]);
//                    }
//                }
//            }
//
//            // 4. Crear nuevo ingreso de restauración
//            $nuevoIngreso = \App\Models\Ingreso::create([
//                'cantidad_ingresada' => $cantidadRestaurada,
//                'fecha' => now()->toDateString(),
//                'material_id' => $stock->material_id,
//                'user_id' => auth()->id() ?? 1,
//                'nota' => '🔄 RESTAURACIÓN DESDE PAPELERA - Stock ID: ' . $stock->id .
//                    ' | ' . $accion .
//                    ' | Cantidad: ' . $cantidadRestaurada .
//                    ' | Material: ' . ($stock->material->nombre ?? 'N/A')
//            ]);
//            $nuevoIngresoId = $nuevoIngreso->id;
//
//            DB::commit();
//
//            // 5. Preparar mensaje de éxito
//            $mensaje = [
//                'title' => '✅ Stock Restaurado',
//                'message' => 'Stock ' . $accion . ' correctamente.',
//                'details' => 'Cantidad restaurada: ' . $cantidadRestaurada .
//                    ' | Ingresos restaurados: ' . $ingresosRestaurados .
//                    ' | Nuevo ingreso: #' . $nuevoIngresoId,
//                'links' => [
//                    ['url' => route('stocks.index'), 'text' => '📦 Ver Stocks'],
//                    ['url' => route('ingresos.index'), 'text' => '📥 Ver Ingresos']
//                ]
//            ];
//
//            return redirect()->route('stocks.index')
//                ->with('success', $mensaje);
//
//        } catch (\Exception $e) {
//            DB::rollBack();
//            \Log::error('Error al restaurar stock ID ' . $id . ': ' . $e->getMessage());
//            return redirect()->route('stocks.trash')
//                ->with('error', '❌ Error al restaurar el stock: ' . $e->getMessage());
//        }
//    }

    /**
     * Restaurar un stock desde la papelera (CON INGRESOS RELACIONADOS).
     */
//    public function restore($id): RedirectResponse
//    {
//        DB::beginTransaction();
//        try {
//            // 1. Obtener stock eliminado con su material
//            $stock = Stock::onlyTrashed()
//                ->with(['material'])
//                ->findOrFail($id);
//
//            // 2. Verificar si hay stock activo existente del mismo material
//            $stockExistente = Stock::where('material_id', $stock->material_id)->first();
//
//            // 3. Restaurar ingresos eliminados relacionados con este material
//            $ingresosRestaurados = \App\Models\Ingreso::onlyTrashed()
//                ->where('material_id', $stock->material_id)
//                ->get();
//
//            $contadorIngresos = 0;
//            foreach ($ingresosRestaurados as $ingreso) {
//                $ingreso->restore();
//                $contadorIngresos++;
//
//                // Agregar nota de restauración
//                $ingreso->update([
//                    'nota' => ($ingreso->nota ?? '') .
//                        "\n🔄 RESTAURADO junto con Stock ID: " . $stock->id .
//                        " - " . now()->format('d/m/Y H:i')
//                ]);
//            }
//
//            // 4. Decidir qué hacer con el stock
//            $mensajeAccion = '';
//
//            if ($stockExistente) {
//                // CASO A: Ya existe stock activo - Sumar cantidades
//                $stockExistente->cantidad += $stock->cantidad;
//                $stockExistente->save();
//
//                // Eliminar permanentemente el stock de la papelera
//                $stock->forceDelete();
//
//                $mensajeAccion = 'Cantidad sumada a stock existente';
//                $stockIdMostrar = $stockExistente->id;
//            } else {
//                // CASO B: No hay stock activo - Restaurar el stock
//                $stock->restore();
//
//                $mensajeAccion = 'Stock restaurado como nuevo';
//                $stockIdMostrar = $stock->id;
//            }
//
//            // 5. Crear nuevo ingreso de restauración
//            $nuevoIngreso = \App\Models\Ingreso::create([
//                'cantidad_ingresada' => $stock->cantidad,
//                'fecha' => now()->toDateString(),
//                'material_id' => $stock->material_id,
//                'user_id' => auth()->id() ?? 1,
//                'nota' => '🔄 RESTAURACIÓN DESDE PAPELERA - ' . $mensajeAccion .
//                    ' | Stock ID Original: ' . $stock->id .
//                    ' | Cantidad: ' . $stock->cantidad .
//                    ' | Material: ' . ($stock->material->nombre ?? 'N/A')
//            ]);
//
//            DB::commit();
//
//            // 6. Preparar mensaje de éxito detallado
//            $mensajeExito = [
//                'title' => '✅ Restauración Completa',
//                'message' => 'Stock y ' . $contadorIngresos . ' ingreso(s) restaurados.',
//                'details' => 'Acción: ' . $mensajeAccion .
//                    ' | Stock ID: ' . $stockIdMostrar .
//                    ' | Cantidad: ' . $stock->cantidad .
//                    ' | Nuevo ingreso: #' . $nuevoIngreso->id,
//                'links' => [
//                    ['url' => route('stocks.index'), 'text' => '📦 Ver Stocks'],
//                    ['url' => route('ingresos.index'), 'text' => '📥 Ver Ingresos'],
//                    ['url' => route('stocks.trash'), 'text' => '🗑️ Ver Papelera']
//                ]
//            ];
//
//            return redirect()->route('stocks.index')
//                ->with('success', $mensajeExito);
//
//        } catch (\Exception $e) {
//            DB::rollBack();
//            \Log::error('Error al restaurar stock ID ' . $id . ': ' . $e->getMessage());
//            return redirect()->route('stocks.trash')
//                ->with('error', '❌ Error al restaurar: ' . $e->getMessage());
//        }
//    }

//    public function restore($id)
//    {
//
//        $stock = Stock::withTrashed()->findOrFail($id);
////        dd($stock->toArray()); // ← Esto mostrará si stock es un array problemático
//
//        // 1. RESTAURAR el stock (esto reactiva el registro)
//        $stock->restore();
//
//        // 2. Buscar el INGRESO relacionado (el que fue eliminado)
//        $ingreso = Ingreso::withTrashed()
//            ->where('stock_id', $stock->id)
//            ->orWhere('lote', $stock->lote) // o el campo que los relaciona
//            ->first();
//
//        // 3. SI existe un ingreso eliminado, restaurarlo también
//        if ($ingreso) {
//            $ingreso->restore();
//
//            // Opcional: Crear un registro de AUDITORÍA (no un nuevo ingreso)
//            Auditoria::create([
//                'tipo' => 'restauracion',
//                'stock_id' => $stock->id,
//                'ingreso_id' => $ingreso->id,
//                'cantidad' => $stock->cantidad,
//                'user_id' => auth()->id(),
//            ]);
//        }
//
//        // 4. Actualizar la cantidad del stock si es necesario
//        // (Esto ya debería estar en relaciones/observers)
//
//        return redirect()->route('stock.papelera')
//            ->with('success', 'Stock restaurado correctamente');
//
//    }

    /**
     * Restaurar un stock desde la papelera.
     */
    public function restore($id): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $stock = Stock::withTrashed()->findOrFail($id);

            // 1. RESTAURAR el stock
            $stock->restore();

            // 2. Buscar y restaurar TODOS los ingresos relacionados con este material
            $ingresos = Ingreso::withTrashed()
                ->where('material_id', $stock->material_id)
                ->get();

            foreach ($ingresos as $ingreso) {
                $ingreso->restore();
            }

            DB::commit();

            return redirect()->route('stocks.trash')
                ->with('success', 'Stock restaurado correctamente. ' .
                    count($ingresos) . ' ingreso(s) relacionados también restaurados.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error al restaurar stock: ' . $e->getMessage());
            return redirect()->route('stocks.trash')
                ->with('error', 'Error al restaurar: ' . $e->getMessage());
        }
    }
    /**
     * Restaurar todos los stocks.
     */
//    public function restoreAll(): RedirectResponse
//    {
//        DB::beginTransaction();
//        try {
//            Stock::onlyTrashed()->restore();
//
//            DB::commit();
//            return redirect()->route('stocks.trash')
//                ->with('success', 'Todos los stocks han sido restaurados correctamente.');
//        } catch (\Exception $e) {
//            DB::rollBack();
//            return redirect()->route('stocks.trash')
//                ->with('error', 'Error al restaurar los stocks: ' . $e->getMessage());
//        }
//    }
//    public function restoreAll(): RedirectResponse
//    {
//        DB::beginTransaction();
//        try {
//            // Obtener todos los stocks eliminados
//            $stocksEliminados = Stock::onlyTrashed()->get();
//            $contador = 0;
//
//            foreach ($stocksEliminados as $stock) {
//                // Restaurar cada stock
//                $stock->restore();
//                $contador++;
//
//                // Crear ingreso por cada stock restaurado
//                \App\Models\Ingreso::create([
//                    'cantidad_ingresada' => $stock->cantidad,
//                    'fecha' => now(),
//                    'material_id' => $stock->material_id,
//                    'user_id' => auth()->id() ?? 1,
//                    'nota' => 'Restauración masiva desde papelera - Stock ID: ' . $stock->id
//                ]);
//            }
//
//            DB::commit();
//
//            return redirect()->route('stocks.index')
//                ->with('success', $contador . ' stocks restaurados correctamente. Se han creado ingresos automáticos.');
//
//        } catch (\Exception $e) {
//            DB::rollBack();
//            return redirect()->route('stocks.trash')
//                ->with('error', 'Error al restaurar los stocks: ' . $e->getMessage());
//        }
//    }
    /**
     * Restaurar todos los stocks (CON INGRESOS RELACIONADOS).
     */
//    public function restoreAll(): RedirectResponse
//    {
//        DB::beginTransaction();
//        try {
//            // Obtener todos los stocks eliminados con relaciones
//            $stocksEliminados = Stock::onlyTrashed()
//                ->with(['material' => function($q) {
//                    $q->with(['ingresos' => function($q2) {
//                        $q2->onlyTrashed();
//                    }]);
//                }])
//                ->get();
//
//            $contadorStocks = 0;
//            $contadorIngresos = 0;
//            $stocksRestaurados = [];
//            $ingresosCreados = [];
//
//            foreach ($stocksEliminados as $stock) {
//                // Verificar si hay stock activo existente
//                $stockExistente = Stock::where('material_id', $stock->material_id)->first();
//
//                if ($stockExistente) {
//                    // Sumar a stock existente
//                    $stockExistente->cantidad += $stock->cantidad;
//                    $stockExistente->save();
//                    $stock->forceDelete();
//                    $tipo = "Sumado";
//                } else {
//                    // Restaurar stock
//                    $stock->restore();
//                    $tipo = "Restaurado";
//                }
//
//                // Restaurar ingresos relacionados
//                if ($stock->material && $stock->material->ingresos) {
//                    foreach ($stock->material->ingresos as $ingreso) {
//                        if ($ingreso->trashed()) {
//                            $ingreso->restore();
//                            $contadorIngresos++;
//                        }
//                    }
//                }
//
//                // Crear nuevo ingreso de restauración
//                $nuevoIngreso = \App\Models\Ingreso::create([
//                    'cantidad_ingresada' => $stock->cantidad,
//                    'fecha' => now()->toDateString(),
//                    'material_id' => $stock->material_id,
//                    'user_id' => auth()->id() ?? 1,
//                    'nota' => '🔄 RESTAURACIÓN MASIVA - ' . $tipo . ' Stock ID: ' . $stock->id
//                ]);
//
//                $stocksRestaurados[] = $stock->id;
//                $ingresosCreados[] = $nuevoIngreso->id;
//                $contadorStocks++;
//            }
//
//            DB::commit();
//
//            $mensaje = [
//                'title' => '✅ Restauración Masiva Completada',
//                'message' => $contadorStocks . ' stock(s) procesado(s) correctamente.',
//                'details' => 'Ingresos restaurados: ' . $contadorIngresos .
//                    ' | Nuevos ingresos creados: ' . count($ingresosCreados),
//                'links' => [
//                    ['url' => route('stocks.index'), 'text' => '📦 Ver Todos los Stocks'],
//                    ['url' => route('ingresos.index'), 'text' => '📥 Ver Todos los Ingresos'],
//                    ['url' => route('stocks.trash'), 'text' => '🗑️ Ver Papelera']
//                ]
//            ];
//
//            return redirect()->route('stocks.index')
//                ->with('success', $mensaje);
//
//        } catch (\Exception $e) {
//            DB::rollBack();
//            \Log::error('Error en restauración masiva: ' . $e->getMessage());
//            return redirect()->route('stocks.trash')
//                ->with('error', '❌ Error en restauración masiva: ' . $e->getMessage());
//        }
//    }

    /**
     * Restaurar todos los stocks (CON INGRESOS RELACIONADOS).
     */
    public function restoreAll(): RedirectResponse
    {
        DB::beginTransaction();
        try {
            // 1. Obtener todos los stocks eliminados
            $stocksEliminados = Stock::onlyTrashed()
                ->with(['material'])
                ->get();

            $contadorStocks = 0;
            $contadorIngresos = 0;
            $detalles = [];

            foreach ($stocksEliminados as $stock) {
                // 2. Restaurar ingresos relacionados
                $ingresosRestaurados = \App\Models\Ingreso::onlyTrashed()
                    ->where('material_id', $stock->material_id)
                    ->get();

                foreach ($ingresosRestaurados as $ingreso) {
                    $ingreso->restore();
                    $contadorIngresos++;
                }

                // 3. Verificar si hay stock activo existente
                $stockExistente = Stock::where('material_id', $stock->material_id)->first();

                if ($stockExistente) {
                    // Sumar a stock existente
                    $stockExistente->cantidad += $stock->cantidad;
                    $stockExistente->save();
                    $stock->forceDelete();
                    $accion = 'Sumado';
                } else {
                    // Restaurar stock
                    $stock->restore();
                    $accion = 'Restaurado';
                }

                // 4. Crear ingreso de restauración para este stock
                $nuevoIngreso = \App\Models\Ingreso::create([
                    'cantidad_ingresada' => $stock->cantidad,
                    'fecha' => now()->toDateString(),
                    'material_id' => $stock->material_id,
                    'user_id' => auth()->id() ?? 1,
                    'nota' => '🔄 RESTAURACIÓN MASIVA - ' . $accion .
                        ' | Stock ID: ' . $stock->id
                ]);

                $detalles[] = 'Stock ' . $stock->id . ': ' . $accion . ' (' . $stock->cantidad . ')';
                $contadorStocks++;
            }

            DB::commit();

            // 5. Preparar mensaje de éxito
            $mensajeExito = [
                'title' => '✅ Restauración Masiva Completada',
                'message' => $contadorStocks . ' stock(s) y ' . $contadorIngresos . ' ingreso(s) restaurados.',
                'details' => implode(' | ', $detalles),
                'links' => [
                    ['url' => route('stocks.index'), 'text' => '📦 Ver Stocks Actualizados'],
                    ['url' => route('ingresos.index'), 'text' => '📥 Ver Ingresos Actualizados']
                ]
            ];

            return redirect()->route('stocks.index')
                ->with('success', $mensajeExito);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error en restauración masiva: ' . $e->getMessage());
            return redirect()->route('stocks.trash')
                ->with('error', '❌ Error en restauración masiva: ' . $e->getMessage());
        }
    }
    /**
     * Eliminar permanentemente un stock.
     */
    public function forceDelete($id): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $stock = Stock::onlyTrashed()->findOrFail($id);
            $stock->forceDelete();

            DB::commit();
            return redirect()->route('stocks.trash')
                ->with('success', 'Stock eliminado permanentemente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('stocks.trash')
                ->with('error', 'Error al eliminar permanentemente el stock: ' . $e->getMessage());
        }
    }

    /**
     * Vaciar la papelera completamente.
     */
    public function emptyTrash(): RedirectResponse
    {
        DB::beginTransaction();
        try {
            Stock::onlyTrashed()->forceDelete();

            DB::commit();
            return redirect()->route('stocks.trash')
                ->with('success', 'Papelera vaciada correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('stocks.trash')
                ->with('error', 'Error al vaciar la papelera: ' . $e->getMessage());
        }
    }
    }
