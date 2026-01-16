<?php

namespace Database\Seeders;

use App\Models\Ubicacion;
use Illuminate\Database\Seeder;

class UbicacionSeeder extends Seeder
{
    // Ubicaciones fijas del sistema
    const UBICACIONES_FIJAS = [
        ['id' => 1, 'ubicacion' => 'Edificio 1'],
        ['id' => 2, 'ubicacion' => 'Edificio 2'],
        ['id' => 3, 'ubicacion' => 'Edificio 3'],
    ];

    public function run(): void
    {
        foreach (self::UBICACIONES_FIJAS as $ubicacion) {
            Ubicacion::updateOrCreate(
                ['id' => $ubicacion['id']],
                [
                    'ubicacion' => $ubicacion['ubicacion'],
                    'protegida' => true,
                ]
            );
        }
    }

    /**
     * Obtener IDs de ubicaciones protegidas
     */
    public static function getIdsProtegidos(): array
    {
        return collect(self::UBICACIONES_FIJAS)
            ->pluck('id')
            ->toArray();
    }
}
