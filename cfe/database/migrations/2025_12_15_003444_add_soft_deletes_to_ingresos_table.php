<?php
// database/migrations/xxxx_add_soft_deletes_to_ingresos_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ingresos', function (Blueprint $table) {
            if (!Schema::hasColumn('ingresos', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    public function down(): void
    {
        Schema::table('ingresos', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
