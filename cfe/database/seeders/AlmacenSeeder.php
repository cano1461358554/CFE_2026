<?php

namespace Database\Seeders;

use App\Models\Almacen;
use App\Models\Ubicacion;
use Illuminate\Database\Seeder;

class AlmacenSeeder extends Seeder
{
    // Almacenes fijos del sistema
    const ALMACENES_FIJOS = [
        ['id' => 1, 'nombre' => 'Almacén de TI', 'ubicacion_id' => 1], // Edificio 1
        ['id' => 2, 'nombre' => 'Almacén de Mantenimiento', 'ubicacion_id' => 2], // edificio 2
        ['id' => 3, 'nombre' => 'Almacén Central', 'ubicacion_id' => 3], // Almacén Central

    ];

    public function run(): void
    {
        // Verificar que las ubicaciones existen
        foreach (self::ALMACENES_FIJOS as $almacen) {
            // Buscar ubicación o usar una por defecto
            $ubicacion = Ubicacion::find($almacen['ubicacion_id']);

            if (!$ubicacion) {
                // Si la ubicación no existe, usar la primera disponible
                $ubicacion = Ubicacion::first();
                if ($ubicacion) {
                    $almacen['ubicacion_id'] = $ubicacion->id;
                } else {
                    // Si no hay ubicaciones, crear una
                    $ubicacion = Ubicacion::create([
                        'ubicacion' => 'Ubicación por Defecto',
                        'protegida' => true,
                    ]);
                    $almacen['ubicacion_id'] = $ubicacion->id;
                }
            }

            Almacen::updateOrCreate(
                ['id' => $almacen['id']],
                [
                    'nombre' => $almacen['nombre'],
                    'ubicacion_id' => $almacen['ubicacion_id'],
                    'protegida' => true,
                ]
            );
        }
    }

    /**
     * Obtener IDs de almacenes protegidos
     */
    public static function getIdsProtegidos(): array
    {
        return collect(self::ALMACENES_FIJOS)
            ->pluck('id')
            ->toArray();
    }
}
