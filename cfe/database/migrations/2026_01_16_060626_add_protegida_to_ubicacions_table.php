<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ubicacions', function (Blueprint $table) {
            if (!Schema::hasColumn('ubicacions', 'protegida')) {
                $table->boolean('protegida')->default(false)->after('ubicacion');
            }
        });
    }

    public function down(): void
    {
        Schema::table('ubicacions', function (Blueprint $table) {
            $table->dropColumn('protegida');
        });
    }
};
