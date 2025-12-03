<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paquetes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('idDestinatario')->nullable()->constrained('clientes')->onDelete('cascade');
            $table->foreignId('idRemitente')->nullable()->constrained('clientes')->onDelete('cascade');
            $table->foreignId('idVehiculo')->nullable()->constrained('vehiculos')->onDelete('cascade');
            $table->string('descripcion');
            $table->decimal('peso', 10, 2);
            $table->decimal('altura', 10, 2);
            $table->date('fechaRegistro')->nullable();
            $table->date('fechaEstimadaEntrega')->nullable();
            $table->string('estadoActual', 50);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paquetes');
    }
};