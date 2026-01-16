<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Stock;
use App\Models\Material;
use App\Models\Ingreso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class IngresoController extends Controller
{
    public function index(Request $request): View
    {
        $ingresos = Ingreso::with(['material', 'user'])->paginate(15);
        return view('ingreso.index', compact('ingresos'))
            ->with('i', ($request->input('page', 1) - 1) * $ingresos->perPage());
    }

    public function create(): View
    {
        $materials = Material::all();
        $users = User::all();
        return view('ingreso.create', compact('materials', 'users'));
    }

    public function store(Request $request)
    {

        try {
            $request->validate([
                'cantidad_ingresada' => 'required|numeric|min:1',
                'fecha' => 'required|date',
                'material_id' => 'required|exists:materials,id',
                'user_id' => 'required|exists:users,id',
                'nota' => 'nullable|string|max:1000', // AGREGAR
            ]);

            $material = Material::findOrFail($request->material_id);

            DB::beginTransaction();

            // Buscar el stock actual (incluyendo eliminados)
            $stock = Stock::withTrashed()->where('material_id', $request->material_id)->first();

            if ($stock) {
                if ($stock->isTrashed()) {
                    // Si el stock estaba eliminado, restaurarlo y actualizar cantidad
                    $stock->restore();
                    $stock->cantidad += $request->cantidad_ingresada;
                } else {
                    // Si el stock existe activo, solo sumar cantidad
                    $stock->cantidad += $request->cantidad_ingresada;
                }
                $stock->save();
            } else {
                // Crear nuevo stock si no existe
                $stock = Stock::create([
                    'material_id' => $request->material_id,
                    'cantidad' => $request->cantidad_ingresada,
                    'almacen_id' => $material->almacen_id,
                ]);
            }

            // Crear el registro de ingreso
            Ingreso::create([
                'cantidad_ingresada' => $request->cantidad_ingresada,
                'fecha' => $request->fecha,
                'material_id' => $request->material_id,
                'user_id' => $request->user_id,
                'nota' => $request->nota, // AGREGAR
            ]);

            DB::commit();

            return redirect()->route('ingresos.index')->with('success', 'Ingreso registrado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al registrar el ingreso: ' . $e->getMessage());
        }

    }

    public function edit($id): View
    {
        $ingreso = Ingreso::findOrFail($id);
        $materials = Material::all();
        $users = User::all();
        return view('ingreso.edit', compact('ingreso', 'materials', 'users'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $validatedData = $request->validate([
                'material_id' => 'required|exists:materials,id',
                'cantidad_ingresada' => 'required|numeric|min:1',
                'fecha' => 'required|date',
                'user_id' => 'required|exists:users,id',
                'nota' => 'nullable|string|max:1000', // AGREGAR
            ]);

            $ingreso = Ingreso::findOrFail($id);

            // Obtener la diferencia de cantidad
            $diferenciaCantidad = $validatedData['cantidad_ingresada'] - $ingreso->cantidad_ingresada;

            // Si el material no ha cambiado
            if ($ingreso->material_id == $validatedData['material_id']) {
                $stock = Stock::withTrashed()->where('material_id', $ingreso->material_id)->first();

                if ($stock) {
                    if ($stock->isTrashed()) {
                        $stock->restore();
                    }
                    $stock->cantidad += $diferenciaCantidad;

                    // Si la cantidad es 0 o menos, eliminar suavemente
                    if ($stock->cantidad <= 0) {
                        $stock->delete();
                    } else {
                        $stock->save();
                    }
                } else {
                    $material = Material::findOrFail($validatedData['material_id']);
                    Stock::create([
                        'material_id' => $validatedData['material_id'],
                        'cantidad' => $validatedData['cantidad_ingresada'],
                        'almacen_id' => $material->almacen_id,
                    ]);
                }
            } else {
                // Si cambió el material, revertir el stock anterior y actualizar el nuevo

                // Revertir stock del material anterior
                $stockAnterior = Stock::withTrashed()->where('material_id', $ingreso->material_id)->first();
                if ($stockAnterior) {
                    if ($stockAnterior->isTrashed()) {
                        $stockAnterior->restore();
                    }
                    $stockAnterior->cantidad -= $ingreso->cantidad_ingresada;

                    if ($stockAnterior->cantidad <= 0) {
                        $stockAnterior->delete();
                    } else {
                        $stockAnterior->save();
                    }
                }

                // Actualizar stock del nuevo material
                $stock = Stock::withTrashed()->where('material_id', $ingreso->material_id)->first();
                if ($stock) {
                    if ($stock->isTrashed()) { // <-- Usa la función del modelo
                        $stock->restore();
                    }
                    $stock->cantidad -= $ingreso->cantidad_ingresada;

                    if ($stock->cantidad <= 0) {
                        $stock->delete(); // Soft delete
                    } else {
                        $stock->save();
                    }
                }
            }

            // Actualizar el registro de ingreso
            $ingreso->update($validatedData);

            DB::commit();
            return redirect()->route('ingresos.index')
                ->with('success', 'Ingreso actualizado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error al actualizar el ingreso: ' . $e->getMessage());
        }
    }

    public function destroy($id): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $ingreso = Ingreso::findOrFail($id);

            // Revertir el stock asociado (soft delete)
            $stock = Stock::withTrashed()->where('material_id', $ingreso->material_id)->first();
            if ($stock) {
                if ($stock->isTrashed()) {
                    $stock->restore();
                }
                $stock->cantidad -= $ingreso->cantidad_ingresada;

                if ($stock->cantidad <= 0) {
                    $stock->delete(); // Soft delete
                } else {
                    $stock->save();
                }
            }

            $ingreso->delete();

            DB::commit();
            return redirect()->route('ingresos.index')
                ->with('success', 'Ingreso eliminado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error al eliminar el ingreso: ' . $e->getMessage());
        }
    }

    public function show($id): View
    {
        $ingreso = Ingreso::with(['material', 'user'])->findOrFail($id);
        return view('ingreso.show', compact('ingreso'));
    }

    // En IngresoController.php
    public function trash(Request $request): View
    {
        $ingresos = Ingreso::onlyTrashed()
            ->with(['material', 'user'])
            ->orderBy('deleted_at', 'desc')
            ->paginate(15);

        return view('ingreso.trash', compact('ingresos'))
            ->with('i', ($request->input('page', 1) - 1) * $ingresos->perPage());
    }

    public function restoreIngreso($id): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $ingreso = Ingreso::onlyTrashed()->findOrFail($id);
            $ingreso->restore();

            // Actualizar stock relacionado
            $stock = Stock::where('material_id', $ingreso->material_id)->first();
            if ($stock) {
                $stock->cantidad += $ingreso->cantidad_ingresada;
                $stock->save();
            }

            DB::commit();
            return redirect()->route('ingresos.trash')
                ->with('success', 'Ingreso restaurado correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('ingresos.trash')
                ->with('error', 'Error al restaurar el ingreso: ' . $e->getMessage());
        }
    }
}
