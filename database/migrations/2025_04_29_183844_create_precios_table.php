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
      Schema::create('precios', function (Blueprint $table) {
    $table->id();
    $table->foreignId('producto_id')->constrained()->onDelete('cascade');
    $table->enum('tipo', ['publico', 'privado', 'agotado']);
    $table->decimal('valor', 10, 2);
    $table->date('fecha_vigencia')->nullable();
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('precios');
    }
};