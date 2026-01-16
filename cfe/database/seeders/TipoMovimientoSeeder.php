<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TipoMovimiento;
use Illuminate\Support\Facades\DB;

class TipoMovimientoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Primero, limpiar la tabla (opcional)
        DB::table('tipo_movimientos')->delete();

        // Definir los tipos de movimiento
        $tipos = [
            [
                'descripcion' => 'Préstamo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'descripcion' => 'Resguardo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'descripcion' => 'Salida Sin Retorno',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'descripcion' => 'Devolución',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Insertar los tipos
        foreach ($tipos as $tipo) {
            TipoMovimiento::create($tipo);
        }

        // Mensaje de confirmación
        $this->command->info('✓ Tipos de movimiento creados:');
        $this->command->info('  - Préstamo');
        $this->command->info('  - Resguardo');
        $this->command->info('  - Salida Sin Retorno');
        $this->command->info('  - Devolución');
    }
}
