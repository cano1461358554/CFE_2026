<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */

    public function up(): void
    {
        Schema::table('movimientos', function (Blueprint $table) {
            // Agregar nuevas columnas
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('usuario_asignado_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('estado')->default('activo'); // activo, devuelto, perdido
            $table->boolean('es_sin_retorno')->default(false);
            $table->text('condiciones')->nullable();
            $table->text('notas')->nullable();
            $table->date('fecha_devolucion_estimada')->nullable();
            $table->date('fecha_devolucion_real')->nullable();

            // Cambiar nombre de tipomovimiento_id para consistencia
            $table->renameColumn('tipomovimiento_id', 'tipo_movimiento_id');

            // Agregar índices
            $table->index(['estado', 'fecha']);
            $table->index(['usuario_asignado_id', 'estado']);
        });
    }

    public function down(): void
    {
        Schema::table('movimientos', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['usuario_asignado_id']);
            $table->dropColumn(['user_id', 'usuario_asignado_id', 'estado', 'es_sin_retorno',
                'condiciones', 'notas', 'fecha_devolucion_estimada', 'fecha_devolucion_real']);
            $table->renameColumn('tipo_movimiento_id', 'tipomovimiento_id');
            $table->dropIndex(['estado', 'fecha']);
            $table->dropIndex(['usuario_asignado_id', 'estado']);
        });
    }


    /**
     * Reverse the migrations.
     */
//    public function down(): void
//    {
//        Schema::dropIfExists('movimientos');
//    }
};
