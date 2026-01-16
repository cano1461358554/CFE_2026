<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
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

            // Renombrar tipomovimiento_id a tipo_movimiento_id (si es necesario)
            if (Schema::hasColumn('movimientos', 'tipomovimiento_id')) {
                $table->renameColumn('tipomovimiento_id', 'tipo_movimiento_id');
            }

            // Agregar soft deletes
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('movimientos', function (Blueprint $table) {
            // Eliminar columnas agregadas
            $table->dropForeign(['user_id']);
            $table->dropForeign(['usuario_asignado_id']);
            $table->dropColumn([
                'user_id',
                'usuario_asignado_id',
                'estado',
                'es_sin_retorno',
                'condiciones',
                'notas',
                'fecha_devolucion_estimada',
                'fecha_devolucion_real'
            ]);

            // Revertir rename
            if (Schema::hasColumn('movimientos', 'tipo_movimiento_id')) {
                $table->renameColumn('tipo_movimiento_id', 'tipomovimiento_id');
            }

            // Eliminar soft deletes
            $table->dropSoftDeletes();
        });
    }
};
