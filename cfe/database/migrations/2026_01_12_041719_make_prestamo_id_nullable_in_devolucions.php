<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('devolucions', function (Blueprint $table) {
            // Si ya tienes prestamo_id, hazlo nullable
            if (Schema::hasColumn('devolucions', 'prestamo_id')) {
                $table->foreignId('prestamo_id')->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('devolucions', function (Blueprint $table) {
            //
        });
    }
};
