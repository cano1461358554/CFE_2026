<?php

namespace Database\Seeders;

use App\Models\TipoMaterial;
use Illuminate\Database\Seeder;

class TipoMaterialSeeder extends Seeder
{
    // Tipos de materiales fijos del sistema
    const TIPOS_FIJOS = [
        ['id' => 1, 'descripcion' => 'Reutilizable'],
        ['id' => 2, 'descripcion' => 'Desechable'],
        ['id' => 3, 'descripcion' => 'Consumible'],
        ['id' => 4, 'descripcion' => 'Perecedero'],
        ['id' => 5, 'descripcion' => 'No Perecedero'],
        ['id' => 6, 'descripcion' => 'Peligroso'],
        ['id' => 7, 'descripcion' => 'Inerte'],
        ['id' => 8, 'descripcion' => 'Reciclable'],
    ];

    public function run(): void
    {
        foreach (self::TIPOS_FIJOS as $tipo) {
            TipoMaterial::updateOrCreate(
                ['id' => $tipo['id']],
                [
                    'descripcion' => $tipo['descripcion'],
                    'protegida' => true,
                ]
            );
        }
    }

    /**
     * Obtener IDs de tipos protegidos
     */
    public static function getIdsProtegidos(): array
    {
        return collect(self::TIPOS_FIJOS)
            ->pluck('id')
            ->toArray();
    }
}
