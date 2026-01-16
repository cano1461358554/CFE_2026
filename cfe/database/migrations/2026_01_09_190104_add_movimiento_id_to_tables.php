<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Agregar movimiento_id a devolucions
        Schema::table('devolucions', function (Blueprint $table) {
            $table->foreignId('movimiento_id')->nullable()->after('prestamo_id')->constrained('movimientos')->onDelete('cascade');
            $table->foreignId('almacen_id')->nullable()->after('descripcion_estado')->constrained('almacens');
        });

        // Agregar movimiento_id a prestamos
        Schema::table('prestamos', function (Blueprint $table) {
            $table->foreignId('movimiento_id')->nullable()->after('user_id')->constrained('movimientos')->onDelete('set null');
        });

        // Agregar índice para búsquedas más rápidas
        Schema::table('devolucions', function (Blueprint $table) {
            $table->index(['prestamo_id', 'movimiento_id']);
        });
    }

    public function down()
    {
        Schema::table('devolucions', function (Blueprint $table) {
            $table->dropForeign(['movimiento_id']);
            $table->dropForeign(['almacen_id']);
            $table->dropColumn(['movimiento_id', 'almacen_id']);
            $table->dropIndex(['prestamo_id', 'movimiento_id']);
        });

        Schema::table('prestamos', function (Blueprint $table) {
            $table->dropForeign(['movimiento_id']);
            $table->dropColumn('movimiento_id');
        });
    }
};
