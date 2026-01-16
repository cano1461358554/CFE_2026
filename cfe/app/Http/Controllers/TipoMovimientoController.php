<?php

namespace App\Http\Controllers;

use App\Models\TipoMovimiento;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\TipoMovimientoRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class TipoMovimientoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $tipoMovimientos = TipoMovimiento::paginate();

        return view('tipo-movimiento.index', compact('tipoMovimientos'))
            ->with('i', ($request->input('page', 1) - 1) * $tipoMovimientos->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $tipoMovimiento = new TipoMovimiento();

        return view('tipo-movimiento.create', compact('tipoMovimiento'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TipoMovimientoRequest $request): RedirectResponse
    {
        TipoMovimiento::create($request->validated());

        return Redirect::route('tipo-movimientos.index')
            ->with('success', 'TipoMovimiento created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $tipoMovimiento = TipoMovimiento::find($id);

        return view('tipo-movimiento.show', compact('tipoMovimiento'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $tipoMovimiento = TipoMovimiento::find($id);

        return view('tipo-movimiento.edit', compact('tipoMovimiento'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TipoMovimientoRequest $request, TipoMovimiento $tipoMovimiento): RedirectResponse
    {
        $tipoMovimiento->update($request->validated());

        return Redirect::route('tipo-movimientos.index')
            ->with('success', 'TipoMovimiento updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        TipoMovimiento::find($id)->delete();

        return Redirect::route('tipo-movimientos.index')
            ->with('success', 'TipoMovimiento deleted successfully');
    }
}
