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
        Schema::create('prestamos', function (Blueprint $table) {
            $table->id();
            $table->date('fecha_prestamo');
            $table->integer('cantidad_prestada');
            $table->text('descripcion');
//            $table->softDeletes();
//            $table->string('resguardo')->nullable(); // Cambia a nullable si no lo quieres requerido
            $table->foreignId('material_id')->constrained('materials')->onDelete('cascade');
//
//            $table->foreignId('personal_id')->constrained()->onDelete('cascade'); // Relación con usuarios
//            $table->foreignId('material_id')->constrained()->onDelete('cascade');
//            $table->foreignId('personal_id')->constrained('personals')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade ');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prestamos');
    }
};
