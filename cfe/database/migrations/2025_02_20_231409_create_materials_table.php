<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**$table->string('email')->nullable(); // Cambia a nullable si no lo quieres requerido
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
//
            $table->string('nombre');
            $table->string('clave')->unique();
            $table->string('marca');
            $table->string('descripcion');
            $table->string('estante')->nullable();


            $table->foreignId('almacen_id')->constrained('almacens')->onDelete('cascade');

            $table->foreignId('categoria_id')->constrained('categorias')->onDelete('cascade');
            $table->foreignId('tipomaterial_id')->constrained('tipo_materials')->onDelete('cascade');
            $table->foreignId('unidadmedida_id')->constrained('unidad_medidas')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('materials');
    }
};
