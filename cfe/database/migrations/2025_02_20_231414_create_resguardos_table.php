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
        Schema::create('resguardos', function (Blueprint $table) {
            $table->id();
            $table->date('fecha_resguardo');
            $table->string('estado');
            $table->foreignId('prestamo_id')->constrained('prestamos')->onDelete('cascade');
//            $table->foreignId('material_id')->constrained('materials')->onDelete('cascade');
//            $table->foreignId('almacen_id')->constrained('almacens')->onDelete('cascade');
//            $table->foreignId('personal_id')->constrained('personals')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resguardos');
    }
};
