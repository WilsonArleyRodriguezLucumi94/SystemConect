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
        Schema::create('company_equipments', function (Blueprint $table) {
            $table->id();
            $table->string('mac_address')->unique(); // Lo que antes guardabas en document_number
            $table->string('name'); // Ej: BASE ARROBLEDA
            $table->string('model')->nullable(); // Ej: LiteAP GPS
            $table->string('mode')->default('AP'); // Para especificar que es un Access Point
            
            // Relación con la IP
            $table->foreignId('ip_address_id')->nullable()->constrained('ip_addresses')->nullOnDelete();
            
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_equipments');
    }
};
