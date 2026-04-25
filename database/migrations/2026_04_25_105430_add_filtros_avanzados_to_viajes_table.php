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
    Schema::table('viajes', function (Blueprint $table) {
        $table->string('mes')->nullable();
        $table->string('rango_edad')->nullable();
        $table->boolean('modo_pro')->default(false);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::table('viajes', function (Blueprint $table) {
        $table->dropColumn(['mes', 'rango_edad', 'modo_pro']);
    });
}
};
