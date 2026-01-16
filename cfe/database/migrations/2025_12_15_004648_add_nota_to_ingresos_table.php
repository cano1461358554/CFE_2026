<?php
// database/migrations/xxxx_add_nota_to_ingresos_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ingresos', function (Blueprint $table) {
            if (!Schema::hasColumn('ingresos', 'nota')) {
                $table->text('nota')->nullable()->after('fecha');
            }
        });
    }

    public function down(): void
    {
        Schema::table('ingresos', function (Blueprint $table) {
            $table->dropColumn('nota');
        });
    }
};
