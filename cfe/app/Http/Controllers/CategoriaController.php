<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\CategoriaRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $categorias = Categoria::paginate();

        return view('categoria.index', compact('categorias'))
            ->with('i', ($request->input('page', 1) - 1) * $categorias->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $categoria = new Categoria();

        return view('categoria.create', compact('categoria'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoriaRequest $request): RedirectResponse
    {
        Categoria::create($request->validated());

        return Redirect::route('categorias.index')
            ->with('success', 'Categoria created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $categoria = Categoria::find($id);

        return view('categoria.show', compact('categoria'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $categoria = Categoria::find($id);

        return view('categoria.edit', compact('categoria'));
    }

    /**
     * Update the specified resource in storage.
     */
//    public function update(CategoriaRequest $request, Categoria $categoria): RedirectResponse
//    {
//        $categoria->update($request->validated());
//
//        return Redirect::route('categorias.index')
//            ->with('success', 'Categoria updated successfully');
//    }
    public function update(Request $request, Categoria $categoria): RedirectResponse
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
        ]);

        // Si es protegida, no permitir cambiar ciertos campos
        if ($categoria->protegida) {
            // Puedes agregar restricciones aquí si es necesario
            // Por ejemplo, no permitir cambiar el nombre de categorías protegidas
             if (isset($data['nombre']) && $data['nombre'] !== $categoria->nombre) {
                 return redirect()->back()
                     ->with('error', 'No se puede cambiar el nombre de una categoría del sistema.');
             }
        }

        $categoria->update($data);

        return redirect()->route('categorias.index')
            ->with('success', 'Categoría actualizada exitosamente.');
    }
    public function destroy($id): RedirectResponse
    {
        try {
            $categoria = Categoria::findOrFail($id);

            // Verificar si está protegida
            if ($categoria->protegida) {
                return redirect()->back()
                    ->with('error', 'No se puede eliminar una categoría del sistema.');
            }

            // Verificar si tiene relaciones (opcional)
            // if ($categoria->materiales()->exists()) {
            //     return redirect()->back()
            //         ->with('error', 'No se puede eliminar la categoría porque tiene materiales asociados.');
            // }

            $categoria->delete();

            return redirect()->route('categorias.index')
                ->with('success', 'Categoría eliminada exitosamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al eliminar: ' . $e->getMessage());
        }
    }

//    public function destroy($id): RedirectResponse
//    {
//        Categoria::find($id)->delete();
//
//        return Redirect::route('categorias.index')
//            ->with('success', 'Categoria deleted successfully');
//    }
}
