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
        Schema::create('historial_envios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('idPaquete')->nullable()->constrained('paquetes')->cascadeOnDelete();
            $table->foreignId('idMotorista')->nullable()->constrained('users')->cascadeOnDelete();
            $table->string('estado', 50);
            $table->string('comentarios')->nullable();
            $table->string('fotoEvidencia')->nullable();
            $table->dateTime('fechaCambio');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historial_envios');
    }
};
