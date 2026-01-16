<?php

namespace App\Console\Commands;

use Database\Seeders\UnidadMedidaSeeder;
use Database\Seeders\CategoriaSeeder;
use Database\Seeders\TipoMaterialSeeder;
use Database\Seeders\UbicacionSeeder;
use Database\Seeders\AlmacenSeeder;
use Illuminate\Console\Command;

class RestaurarDatosFijos extends Command
{
    protected $signature = 'datos-fijos:restaurar';
    protected $description = 'Restaura todos los datos fijos del sistema';

    public function handle()
    {
        $this->info('=== RESTAURANDO TODOS LOS DATOS FIJOS DEL SISTEMA ===');

        $this->info("\n📦 1. Restaurando Unidades de Medida...");
        $this->call(UnidadMedidaSeeder::class);
        $this->line('   ✓ 7 unidades de medida restauradas');

        $this->info("\n🏷️  2. Restaurando Categorías...");
        $this->call(CategoriaSeeder::class);
        $this->line('   ✓ 6 categorías restauradas');

        $this->info("\n🧱 3. Restaurando Tipos de Material...");
        $this->call(TipoMaterialSeeder::class);
        $this->line('   ✓ 8 tipos de material restaurados');

        $this->info("\n📍 4. Restaurando Ubicaciones...");
        $this->call(UbicacionSeeder::class);
        $this->line('   ✓ 8 ubicaciones restauradas');

        $this->info("\n🏢 5. Restaurando Almacenes...");
        $this->call(AlmacenSeeder::class);
        $this->line('   ✓ 6 almacenes restaurados');

        $this->info("\n" . str_repeat('=', 60));
        $this->info('✅ ¡TODOS LOS DATOS FIJOS HAN SIDO RESTAURADOS EXITOSAMENTE!');
        $this->info(str_repeat('-', 60));
        $this->info('📊 RESUMEN DE DATOS RESTAURADOS:');
        $this->info(str_repeat('-', 60));
        $this->info('• 📏 Unidades de Medida: 7 registros protegidos');
        $this->info('• 🏷️  Categorías: 6 registros protegidos');
        $this->info('• 🧱 Tipos de Material: 8 registros protegidos');
        $this->info('• 📍 Ubicaciones: 8 registros protegidos');
        $this->info('• 🏢 Almacenes: 6 registros protegidos');
        $this->info(str_repeat('-', 60));
        $this->info('📈 TOTAL: 35 registros fijos protegidos del sistema');
        $this->info(str_repeat('=', 60));

        // Opcional: Verificación de conteo
        $this->info("\n🔍 Verificando conteo de registros protegidos...");

        if (class_exists('App\Models\UnidadMedida')) {
            $unidades = \App\Models\UnidadMedida::where('protegida', true)->count();
            $this->line("   • Unidades de Medida: {$unidades}/7");
        }

        if (class_exists('App\Models\Categoria')) {
            $categorias = \App\Models\Categoria::where('protegida', true)->count();
            $this->line("   • Categorías: {$categorias}/6");
        }

        if (class_exists('App\Models\TipoMaterial')) {
            $tipos = \App\Models\TipoMaterial::where('protegida', true)->count();
            $this->line("   • Tipos de Material: {$tipos}/8");
        }

        if (class_exists('App\Models\Ubicacion')) {
            $ubicaciones = \App\Models\Ubicacion::where('protegida', true)->count();
            $this->line("   • Ubicaciones: {$ubicaciones}/8");
        }

        if (class_exists('App\Models\Almacen')) {
            $almacenes = \App\Models\Almacen::where('protegida', true)->count();
            $this->line("   • Almacenes: {$almacenes}/6");
        }

        return Command::SUCCESS;
    }
}
