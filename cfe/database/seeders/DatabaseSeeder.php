<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        $this->call([
            UnidadMedidaSeeder::class, // Agregar esta línea
            CategoriaSeeder::class, // Agregar esta línea
            TipoMaterialSeeder::class, // Agregar esta línea
            UbicacionSeeder::class, // Agregar esta línea
            AlmacenSeeder::class, // Agregar esta línea
         ]);
    }
}
