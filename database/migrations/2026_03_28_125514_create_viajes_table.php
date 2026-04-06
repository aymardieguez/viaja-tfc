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
        Schema::create('viajes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('titulo');
            $table->string('destino');
            $table->integer('presupuesto')->nullable();
            $table->integer('noches');
            $table->string('imagen')->nullable();
            $table->boolean('favorito')->default(false);
            $table->integer('personas')->default(1);
            $table->json('intereses')->nullable();
            $table->json('filtros_ia')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('viajes');
    }
};
