<?php

namespace App\Http\Controllers;

use App\Models\Almacen;
use App\Models\Material;
use App\Models\Categoria;
use App\Models\Stock;
use App\Models\TipoMaterial;
use App\Models\UnidadMedida;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class MaterialController extends Controller
{
//    public function index(Request $request): View
//    {
//        $materials = Material::with(['categoria', 'tipomaterial', 'unidadmedida','almacen'])->paginate();
//
//        return view('material.index', compact('materials'))
//            ->with('i', ($request->input('page', 1) - 1) * $materials->perPage());
//    }
    public function index()
    {
        $materials = Material::query()
            ->when(request('nombre'), function($query) {
                return $query->where('nombre', 'like', '%'.request('nombre').'%');
            })
            ->when(request('almacen'), function($query) {
                return $query->where('almacen_id', request('almacen'));
            })
            ->when(request('estante'), function($query) {
                return $query->where('estante', 'like', '%'.request('estante').'%');
            })
            ->with(['almacen', 'categoria', 'tipomaterial', 'unidadmedida'])
            ->paginate();

        $almacenes = Almacen::all(); // Obtener todos los almacenes

        return view('material.index', compact('materials', 'almacenes'));
    }

    public function create(): View
    {
        $categorias = Categoria::all();
        $tiposMaterial = TipoMaterial::all();
        $unidadesMedida = UnidadMedida::all();
        $almacens = Almacen::all();

        return view('material.create', compact('categorias', 'tiposMaterial', 'unidadesMedida', 'almacens'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'marca' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'estante' => 'required|string|max:50',
            'categoria_id' => 'required|exists:categorias,id',
            'tipomaterial_id' => 'required|exists:tipo_materials,id',
            'almacen_id' => 'required|exists:almacens,id',
            'unidadmedida_id' => 'required|exists:unidad_medidas,id',
        ]);

        $materialExistente = Material::where('nombre', $request->nombre)->first();

        if ($materialExistente) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['nombre' => 'Oh no, parece que este material ya fue creado.']);
        }

        $nombreMaterial = $request->nombre;
        $categoria = Categoria::find($request->categoria_id);
        $tipoMaterial = TipoMaterial::find($request->tipomaterial_id);

        $inicialMaterial = strtoupper(substr($nombreMaterial, 0, 2));
        $inicialCategoria = strtoupper(substr($categoria->nombre, 0, 2));
        $inicialTipoMaterial = strtoupper(substr($tipoMaterial->nombre, 0, 2));

        $siguienteNumero = Material::count() + 1;
        $numeroControl = str_pad($siguienteNumero, 3, '0', STR_PAD_LEFT);

        $claveCompuesta = $inicialMaterial . $inicialCategoria . $inicialTipoMaterial . $numeroControl;

        $material = Material::create([
            'nombre' => $request->nombre,
            'clave' => $claveCompuesta,
            'marca' => $request->marca,
            'descripcion' => $request->descripcion,
            'estante' => $request->estante,
            'categoria_id' => $request->categoria_id,
            'tipomaterial_id' => $request->tipomaterial_id,
            'unidadmedida_id' => $request->unidadmedida_id,
            'almacen_id' => $request->almacen_id,
        ]);

        return Redirect::route('materials.index')
            ->with('success', 'Material creado exitosamente.');
    }

    public function show($id): View
    {
        $material = Material::with(['categoria', 'tipomaterial', 'unidadmedida', 'almacen'])->findOrFail($id);

        return view('material.show', compact('material'));
    }

//    public function edit($id): View
//    {
//        $material = Material::findOrFail($id);
//        $categorias = Categoria::all();
//        $tiposMaterial = TipoMaterial::all();
//        $unidadesMedida = UnidadMedida::all();
//        $almacens = Almacen::all();
//
//        return view('material.edit', compact('material', 'categorias', 'tiposMaterial', 'unidadesMedida', 'almacens'));
//    }
//
//    public function update(Request $request, Material $material)
//    {
//        $request->validate([
//            'nombre' => 'required|string|max:255',
//            'marca' => 'required|string|max:255',
//            'descripcion' => 'nullable|string',
//            'estante' => 'required|string|max:50',
//            'categoria_id' => 'required|exists:categorias,id',
//            'tipomaterial_id' => 'required|exists:tipo_materials,id',
//            'almacen_id' => 'required|exists:almacens,id',
//            'unidadmedida_id' => 'required|exists:unidad_medidas,id',
//        ]);
//
//        $material->update([
//            'nombre' => $request->nombre,
//            'marca' => $request->marca,
//            'descripcion' => $request->descripcion,
//            'estante' => $request->estante,
//            'categoria_id' => $request->categoria_id,
//            'tipomaterial_id' => $request->tipomaterial_id,
//            'unidadmedida_id' => $request->unidadmedida_id,
//            'almacen_id' => $request->almacen_id,
//        ]);
//
//        return Redirect::route('materials.index')
//            ->with('success', 'Material actualizado exitosamente.');
//    }

    public function edit($id): View
    {
        $material = Material::with(['categoria', 'tipomaterial', 'unidadmedida', 'almacen'])->findOrFail($id);
        $categorias = Categoria::all();
        $tiposMaterial = TipoMaterial::all();
        $unidadesMedida = UnidadMedida::all();
        $almacens = Almacen::all();

        return view('material.edit', compact('material', 'categorias', 'tiposMaterial', 'unidadesMedida', 'almacens'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'marca' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'estante' => 'required|string|max:50',
            'categoria_id' => 'required|exists:categorias,id',
            'tipomaterial_id' => 'required|exists:tipo_materials,id',
            'almacen_id' => 'required|exists:almacens,id',
            'unidadmedida_id' => 'required|exists:unidad_medidas,id',
        ]);

        $material = Material::findOrFail($id);

        // Verificar si el nombre ha cambiado
        if ($material->nombre !== $request->nombre) {
            $materialExistente = Material::where('nombre', $request->nombre)
                ->where('id', '!=', $id)
                ->first();

            if ($materialExistente) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['nombre' => 'Oh no, parece que este material ya existe con otro registro.']);
            }

            // Si el nombre cambió, actualizar la clave
            $nombreMaterial = $request->nombre;
            $categoria = Categoria::find($request->categoria_id);
            $tipoMaterial = TipoMaterial::find($request->tipomaterial_id);

            $inicialMaterial = strtoupper(substr($nombreMaterial, 0, 2));
            $inicialCategoria = strtoupper(substr($categoria->nombre, 0, 2));
            $inicialTipoMaterial = strtoupper(substr($tipoMaterial->nombre, 0, 2));

            // Mantener el mismo número de control que tenía originalmente
            $numeroControl = substr($material->clave, -3);
            $claveCompuesta = $inicialMaterial . $inicialCategoria . $inicialTipoMaterial . $numeroControl;

            $material->clave = $claveCompuesta;
        }

        $material->update([
            'nombre' => $request->nombre,
            'marca' => $request->marca,
            'descripcion' => $request->descripcion,
            'estante' => $request->estante,
            'categoria_id' => $request->categoria_id,
            'tipomaterial_id' => $request->tipomaterial_id,
            'unidadmedida_id' => $request->unidadmedida_id,
            'almacen_id' => $request->almacen_id,
        ]);

        return Redirect::route('materials.index')
            ->with('success', 'Material actualizado exitosamente.');
    }

//    public function destroy($id)
//    {
//        $material = Material::findOrFail($id);
//        $nombreMaterial = $material->nombre;
//        $material->delete();
//
//        $this->reordenarClaves();
//
//        return Redirect::route('materials.index')
//            ->with('success', "El material '$nombreMaterial' ha sido eliminado. Las claves han sido reordenadas.");
//    }
    public function destroy($id)
    {
        try {
            $material = Material::findOrFail($id);
            $material->delete();
            $this->reordenarClaves();

            return redirect()->route('materials.index')
                ->with('success', 'Material eliminado correctamente');

        } catch (\Exception $e) {
            // Si es error de validación de clave
            if ($e instanceof ModelNotFoundException) {
                return redirect()->back()
                    ->with('error', $e->getMessage());
            }

            // Para otros errores
            return redirect()->back()
                ->with('error', 'Error al eliminar el material');
        }
    }

//    private function reordenarClaves()
//    {
//        $materials = Material::orderBy('id')->get();
//        $contador = 1;
//
//        foreach ($materials as $material) {
//            $nombreMaterial = $material->nombre;
//            $categoria = $material->categoria;
//            $tipoMaterial = $material->tipomaterial;
//
//            $inicialMaterial = strtoupper(substr($nombreMaterial, 0, 2));
//            $inicialCategoria = strtoupper(substr($categoria->nombre, 0, 2));
//            $inicialTipoMaterial = strtoupper(substr($tipoMaterial->nombre, 0, 2));
//
//            $numeroControl = str_pad($contador, 3, '0', STR_PAD_LEFT);
//            $claveCompuesta = $inicialMaterial . $inicialCategoria . $inicialTipoMaterial . $numeroControl;
//
//            $material->clave = $claveCompuesta;
//            $material->save();
//            $contador++;
//        }
//    }

    private function reordenarClaves()
    {
        // Obtener todos los materiales ordenados por ID (incluyendo eliminados si es necesario)
        $materials = Material::withTrashed()->orderBy('id')->get();
        $contador = 1;

        DB::beginTransaction();
        try {
            foreach ($materials as $material) {
                // Generar el número con 4 dígitos (MAT-0001, MAT-0002, etc.)
                $numero = str_pad($contador, 4, '0', STR_PAD_LEFT);
                $nuevaClave = 'MAT-' . $numero;

                // Verificar si la clave ya existe (por si acaso)
                $claveExistente = Material::withTrashed()
                    ->where('clave', $nuevaClave)
                    ->where('id', '!=', $material->id)
                    ->exists();

                if (!$claveExistente) {
                    $material->clave = $nuevaClave;
                    $material->save();
                } else {
                    // Si por alguna razón existe, buscar el siguiente disponible
                    do {
                        $contador++;
                        $numero = str_pad($contador, 4, '0', STR_PAD_LEFT);
                        $nuevaClave = 'MAT-' . $numero;
                    } while (Material::withTrashed()
                        ->where('clave', $nuevaClave)
                        ->where('id', '!=', $material->id)
                        ->exists());

                    $material->clave = $nuevaClave;
                    $material->save();
                }

                $contador++;
            }

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }
    public function getStock(Material $material)
    {
        // Para cuando la tabla stock tiene múltiples registros por material
        $totalStock = Stock::where('material_id', $material->id)
            ->sum('cantidad'); // Ajusta el nombre de la columna

        return response()->json([
            'stock' => $totalStock,
            'unidad' => $material->unidadmedida->descripcion_unidad ?? 'unidades'
        ]);
    }
    public function eliminados()
    {
        $materiales = Material::onlyTrashed()
            ->with(['stocks' => function($query) {
                $query->withTrashed();
            }])
            ->paginate(10);

        return view('material.eliminados', compact('materiales'));
    }

    public function restaurar($id)
    {
        DB::beginTransaction();
        try {
            $material = Material::onlyTrashed()->findOrFail($id);

            // Restaurar el material
            $material->restore();

            // Restaurar sus stocks asociados
            $material->stocks()->withTrashed()->restore();

            DB::commit();

            return redirect()->route('materials.eliminados')
                ->with('success', 'Material y stock restaurados correctamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error al restaurar: ' . $e->getMessage());
        }
    }

    //elementos nuevos para la implementacion de archivos csv
//    public function showImportForm()
//    {
//        return view('materials.import'); // Cambiado de 'material.import' a 'materials.import'
//    }
    public function showImportForm()
    {
        if (!view()->exists('material.import')) {
            abort(500, 'La vista no existe');
        }

        return view('material.import');
    }
    public function downloadTemplate()
    {
        $headers = [
            'nombre', 'marca', 'descripcion', 'estante',
            'categoria_id', 'tipomaterial_id', 'unidadmedida_id', 'almacen_id'
        ];

        $filename = "plantilla_importacion_materiales.csv";
        $handle = fopen('php://output', 'w');
        fputcsv($handle, $headers);
        fclose($handle);

        return response('', 200, [
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

        // Procesar el archivo CSV
        $csvData = array_map('str_getcsv', file($file->getRealPath()));

        if ($hasHeader) {
            $header = array_map('strtolower', array_shift($csvData));
        } else {
            $header = [
                'nombre', 'marca', 'descripcion', 'estante',
                'categoria_id', 'tipomaterial_id', 'unidadmedida_id', 'almacen_id'
            ];
        }

        $importedCount = 0;
        $errors = [];
        $duplicates = [];

        DB::beginTransaction();

        try {
            foreach ($csvData as $index => $row) {
                if (empty(array_filter($row))) continue;

                if (count($row) != count($header)) {
                    $errors[$index] = ['Número de columnas no coincide'];
                    continue;
                }

                $rowData = array_combine($header, $row);

                // Validar datos básicos
                $validator = Validator::make($rowData, [
                    'nombre' => 'required|string|max:255',
                    'marca' => 'required|string|max:255',
                    'estante' => 'required|string|max:50',
                    'categoria_id' => 'required|exists:categorias,id',
                    'tipomaterial_id' => 'required|exists:tipo_materials,id',
                    'unidadmedida_id' => 'required|exists:unidad_medidas,id',
                    'almacen_id' => 'required|exists:almacens,id',
                ]);

                if ($validator->fails()) {
                    $errors[$index] = $validator->errors()->all();
                    continue;
                }

                // Verificar si el material ya existe
                $materialExistente = Material::where('nombre', $rowData['nombre'])->first();

                if ($materialExistente) {
                    $duplicates[$index] = $rowData['nombre'];
                    continue;
                }

                // Generar la clave automática (similar a tu método store)
                $nombreMaterial = $rowData['nombre'];
                $categoria = Categoria::find($rowData['categoria_id']);
                $tipoMaterial = TipoMaterial::find($rowData['tipomaterial_id']);

                $inicialMaterial = strtoupper(substr($nombreMaterial, 0, 2));
                $inicialCategoria = strtoupper(substr($categoria->nombre, 0, 2));
                $inicialTipoMaterial = strtoupper(substr($tipoMaterial->nombre, 0, 2));

                $siguienteNumero = Material::withTrashed()->count() + 1;
                $numeroControl = str_pad($siguienteNumero, 3, '0', STR_PAD_LEFT);

                $claveCompuesta = $inicialMaterial . $inicialCategoria . $inicialTipoMaterial . $numeroControl;

                // Crear el material
                $material = Material::create([
                    'nombre' => $rowData['nombre'],
                    'clave' => $claveCompuesta,
                    'marca' => $rowData['marca'],
                    'descripcion' => $rowData['descripcion'] ?? null,
                    'estante' => $rowData['estante'],
                    'categoria_id' => $rowData['categoria_id'],
                    'tipomaterial_id' => $rowData['tipomaterial_id'],
                    'unidadmedida_id' => $rowData['unidadmedida_id'],
                    'almacen_id' => $rowData['almacen_id'],
                ]);

                $importedCount++;
            }

            DB::commit();

            return redirect()->route('materials.index')
                ->with([
                    'success' => "Se importaron $importedCount materiales correctamente.",
                    'warning' => count($duplicates) > 0 ?
                        'Se omitieron ' . count($duplicates) . ' materiales con nombres duplicados.' : null,
                    'import_errors' => $errors
                ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Error durante la importación: ' . $e->getMessage())
                ->withInput();
        }
    }
}

