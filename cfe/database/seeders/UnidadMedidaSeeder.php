<?php
namespace Database\Seeders;

use App\Models\UnidadMedida;
use Illuminate\Database\Seeder;

class UnidadMedidaSeeder extends Seeder
{
    // IDs fijos que deben estar protegidos
    const UNIDADES_FIJAS = [
        1 => 'Kilogramos',
        2 => 'Gramos',
        3 => 'Litros',
        4 => 'Mililitros',
        5 => 'Unidades',
        6 => 'Metros',
        7 => 'Centímetros',
        8 => 'Pulgadas',
        9 => 'Cajas',
        10 => 'Cubetas',

    ];

    public function run(): void
    {
        foreach (self::UNIDADES_FIJAS as $id => $descripcion) {
            UnidadMedida::updateOrCreate(
                ['id' => $id],
                [
                    'descripcion_unidad' => $descripcion,
                    'protegida' => true, // ¡IMPORTANTE! Marcar como protegido
                ]
            );
        }
    }
}
//
//namespace Database\Seeders;
//
//use App\Models\UnidadMedida;
//use Illuminate\Database\Seeder;
//
//class UnidadMedidaSeeder extends Seeder
//{
//    public function run(): void
//    {
//        $unidades = [
//            ['id' => 1, 'descripcion_unidad' => 'Kilogramos'],
//            ['id' => 2, 'descripcion_unidad' => 'Gramos'],
//            ['id' => 3, 'descripcion_unidad' => 'Litros'],
//            ['id' => 4, 'descripcion_unidad' => 'Mililitros'],
//            ['id' => 5, 'descripcion_unidad' => 'Unidades'],
//            ['id' => 6, 'descripcion_unidad' => 'Metros'],
//            ['id' => 7, 'descripcion_unidad' => 'Centímetros'],
//            ['id' => 8, 'descripcion_unidad' => 'Pulgadas'],
//            ['id' => 9, 'descripcion_unidad' => 'Cajas'],
//            ['id' => 10, 'descripcion_unidad' => 'Cubetas'],
//
//        ];
//
//        foreach ($unidades as $unidad) {
//            UnidadMedida::updateOrCreate(
//                ['id' => $unidad['id']],
//                ['descripcion_unidad' => $unidad['descripcion_unidad']]
//            );
//        }
//    }
//}
