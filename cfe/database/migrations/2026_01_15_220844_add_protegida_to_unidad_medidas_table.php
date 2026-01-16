 <?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('unidad_medidas', function (Blueprint $table) {
            if (!Schema::hasColumn('unidad_medidas', 'protegida')) {
                $table->boolean('protegida')->default(false)->after('descripcion_unidad');
            }
        });

        // Marcar las unidades fijas como protegidas
        \DB::table('unidad_medidas')->whereIn('id', [1,2,3,4,5,6,7,8,9,10])->update(['protegida' => true]);
    }

    public function down(): void
    {
        Schema::table('unidad_medidas', function (Blueprint $table) {
            $table->dropColumn('protegida');
        });
    }
};
