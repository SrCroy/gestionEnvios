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
        Schema::create('asignaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('idPaquete')->nullable()->constrained('paquetes')->cascadeOnDelete();
            $table->foreignId('idMotorista')->nullable()->constrained('users')->cascadeOnDelete();
            $table->foreignId('idVehiculo')->nullable()->constrained('vehiculos')->cascadeOnDelete();
            $table->dateTime('fechaAsignacion')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asignaciones');
    }
};
