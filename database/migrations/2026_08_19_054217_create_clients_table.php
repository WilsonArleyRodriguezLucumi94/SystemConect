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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('document_number')->unique();
            $table->string('full_name');
            $table->string('phone');
            $table->string('address');
            //$table->string('ip_address')->nullable(); // Útil para futura conexión con Mikrotik
            $table->foreignId('ip_address_id')->nullable()->constrained('ip_addresses')->nullOnDelete();
            $table->foreignId('plan_id')->constrained('plans');
            $table->date('billing_day'); // Día del mes en que se le cobra (ej. los 15 de cada mes)
            $table->enum('status', ['active', 'suspended'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
