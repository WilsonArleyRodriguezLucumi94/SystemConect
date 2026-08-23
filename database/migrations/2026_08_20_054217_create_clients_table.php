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
            $table->string('document_number');
            $table->string('full_name');
            $table->string('phone')->default('0000000000');
            $table->string('address')->nullable();
            
            // Relaciones
            $table->foreignId('plan_id')->constrained('plans');
            $table->foreignId('ip_address_id')->nullable()->constrained('ip_addresses')->nullOnDelete();
            $table->foreignId('company_equipment_id')->nullable()->constrained('company_equipments')->nullOnDelete();
            
            // Control de pagos
            $table->integer('billing_day')->default(1); // Día del mes para referencia
            $table->date('next_due_date')->nullable();  // Fecha exacta del próximo cobro
            
            $table->string('status')->default('active');
            $table->foreignId('router_id')->nullable()->constrained('routers')->nullOnDelete();
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
