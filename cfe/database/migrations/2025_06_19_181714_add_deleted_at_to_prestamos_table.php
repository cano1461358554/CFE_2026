<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeletedAtToPrestamosTable extends Migration
{
    public function up()
    {
        Schema::table('prestamos', function (Blueprint $table) {
            $table->softDeletes(); // Esto agregará la columna deleted_at
        });
    }

    public function down()
    {
        Schema::table('prestamos', function (Blueprint $table) {
            $table->dropSoftDeletes(); // Esto eliminará la columna
        });
    }
}
