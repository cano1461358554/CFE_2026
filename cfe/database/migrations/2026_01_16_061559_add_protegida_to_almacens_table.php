<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('almacens', function (Blueprint $table) {
            if (!Schema::hasColumn('almacens', 'protegida')) {
                $table->boolean('protegida')->default(false)->after('ubicacion_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('almacens', function (Blueprint $table) {
            $table->dropColumn('protegida');
        });
    }
};
