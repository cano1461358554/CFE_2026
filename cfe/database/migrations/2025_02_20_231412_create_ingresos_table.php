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
        Schema::create('ingresos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('material_id')->constrained('materials')->onDelete('cascade');
//            $table->foreignId('almacen_id')->constrained('almacens')->onDelete('cascade');
//            $table->foreignId('categoria_id')->constrained('categorias')->onDelete('cascade');
//            $table->foreignId('tipo_material_id')->constrained('tipo-materials')->onDelete('cascade');
//            $table->foreignId('unidad_medida_id')->constrained('uniadad_medidas')->onDelete('cascade');
//            $table->foreignId('unidad_medida_id')->constrained('uniadad_medidas')->onDelete('cascade');
//            $table->foreignId('personal_id')->constrained('personals')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade ');

            $table->integer('cantidad_ingresada');
            $table->date('fecha');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingresos');
    }
};
