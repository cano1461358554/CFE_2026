<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // En la migración generada:
    public function up()
    {
        Schema::table('materials', function (Blueprint $table) {
            // Elimina el índice único tradicional
            $table->dropUnique(['clave']);

            // Agrega un índice normal (no único)
            $table->index('clave');
        });
    }

    public function down()
    {
        Schema::table('materials', function (Blueprint $table) {
            $table->dropIndex(['clave']);
            $table->unique('clave');
        });
    }
};
