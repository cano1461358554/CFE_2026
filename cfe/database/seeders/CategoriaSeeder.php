<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Seeder;

class CategoriaSeeder extends Seeder
{
    // Categorías fijas del sistema
    const CATEGORIAS_FIJAS = [
        ['id' => 1, 'nombre' => 'Materiales de Construcción', 'descripcion' => 'Materiales básicos para construcción'],
        ['id' => 2, 'nombre' => 'Herramientas', 'descripcion' => 'Herramientas manuales y eléctricas'],
        ['id' => 3, 'nombre' => 'Equipo de Seguridad', 'descripcion' => 'Equipo de protección personal'],
        ['id' => 4, 'nombre' => 'Material Eléctrico', 'descripcion' => 'Componentes y cables eléctricos'],
        ['id' => 5, 'nombre' => 'Plomería', 'descripcion' => 'Tuberías y accesorios de plomería'],
        ['id' => 6, 'nombre' => 'Pinturas y Acabados', 'descripcion' => 'Pinturas, barnices y acabados'],
    ];

    public function run(): void
    {
        foreach (self::CATEGORIAS_FIJAS as $categoria) {
            Categoria::updateOrCreate(
                ['id' => $categoria['id']],
                [
                    'nombre' => $categoria['nombre'],
                    'descripcion' => $categoria['descripcion'],
                    'protegida' => true,
                ]
            );
        }
    }

    /**
     * Obtener IDs de categorías protegidas
     */
    public static function getIdsProtegidos(): array
    {
        return collect(self::CATEGORIAS_FIJAS)
            ->pluck('id')
            ->toArray();
    }
}
