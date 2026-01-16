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
        Schema::create('devolucions', function (Blueprint $table) {
            $table->id();
            $table->date('fecha_devolucion');
            $table->integer('cantidad_devuelta');
            $table->text('descripcion_estado');
//            $table->foreignId('prestamo_id')->constrained('prestamos')->onDelete('cascade');
            $table->foreignId('movimiento_id')->nullable()->constrained('movimientos')->onDelete('cascade'); // Quitamos ->after()
//            $table->foreignId('movimiento_id')->nullable()->after('prestamo_id')->constrained('movimientos');
//            $table->foreignId('material_id')->constrained('materials')->onDelete('cascade');
            $table->foreignId('almacen_id')->constrained('almacens')->onDelete('cascade');
//            $table->foreignId('personal_id')->constrained('personals')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devolucions');
    }
};
