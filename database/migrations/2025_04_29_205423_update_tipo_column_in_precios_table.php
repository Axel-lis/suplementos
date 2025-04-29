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
        Schema::table('precios', function (Blueprint $table) {
            // Definir los valores posibles para 'tipo' como ENUM
            $table->enum('tipo', ['publico', 'privado', 'costo', 'preferencial'])->default('publico')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('precios', function (Blueprint $table) {
            // Revertir el campo 'tipo' a string si es necesario
            $table->string('tipo')->change();
        });
    }
};