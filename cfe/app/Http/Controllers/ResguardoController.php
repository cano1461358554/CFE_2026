<?php

namespace App\Http\Controllers;

//use App\Models\Almacen;
//use App\Models\Material;
use App\Models\Resguardo;
use App\Models\Prestamo;
use App\Models\Personal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ResguardoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $resguardos = Resguardo::with(['prestamo', 'personal'])->paginate(10);
        return view('resguardo.index', compact('resguardos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $prestamos = Prestamo::all();
//        $materials = Material::all();
//        $almacens = Almacen::all();
        $personals = Personal::all();

        return view('resguardo.create', compact('prestamos', 'personals'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'fecha_resguardo' => 'required|date',
            'estado' => 'required|string|in:activo,inactivo',
            'prestamo_id' => 'required|exists:prestamos,id',
//            'material_id' => 'nullable|exists:materials,id',
//            'almacen_id' => 'nullable|exists:almacens,id',
            'personal_id' => 'required|exists:personals,id',
        ]);

        // Crear el resguardo
        Resguardo::create([
            'fecha_resguardo' => $request->fecha_resguardo,
            'estado' => $request->estado,
            'prestamo_id' => $request->prestamo_id,
//            'material_id' => $request->material_id,
//            'almacen_id' => $request->almacen_id,
            'personal_id' => $request->personal_id,
        ]);

        return redirect()->route('resguardos.index')
            ->with('success', 'Resguardo creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        // Obtener el resguardo por su ID
        $resguardo = Resguardo::findOrFail($id);

        return view('resguardo.show', compact('resguardo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Resguardo $resguardo)
    {
        $prestamos = Prestamo::all();
//        $materials = Material::all();
//        $almacens = Almacen::all();
        $personals = Personal::all();

        // Pasar los datos a la vista
        return view('resguardo.edit', compact('resguardo', 'prestamos', 'personals'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Resguardo $resguardo): RedirectResponse
    {
        $request->validate([
            'fecha_resguardo' => 'required|date',
            'estado' => 'required|string|in:activo,inactivo',
            'prestamo_id' => 'required|exists:prestamos,id',
//            'material_id' => 'nullable|exists:materials,id',
//            'almacen_id' => 'nullable|exists:almacens,id',
            'personal_id' => 'required|exists:personals,id',
        ]);

        $resguardo->update($request->all());

        return redirect()->route('resguardos.index')
            ->with('success', 'Resguardo actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): RedirectResponse
    {
        Resguardo::findOrFail($id)->delete();

        return redirect()->route('resguardos.index')
            ->with('success', 'Resguardo eliminado correctamente.');
    }
    public function Personal($personalId)
    {
        return Prestamo::where('personal_id', $personalId)->get();
    }
}
