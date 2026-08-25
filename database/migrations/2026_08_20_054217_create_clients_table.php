<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('document_number')->nullable();
            $table->string('full_name');
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->integer('billing_day')->nullable(); // Día de corte/facturación (Ej: 1 al 31)
            $table->date('next_due_date')->nullable();
            $table->enum('status', ['active', 'suspended'])->default('active');
            
            // Llaves foráneas
            $table->foreignId('plan_id')->nullable()->constrained('plans')->nullOnDelete();
            $table->foreignId('ip_address_id')->nullable()->constrained('ip_addresses')->nullOnDelete();
            $table->foreignId('router_id')->nullable()->constrained('routers')->nullOnDelete();
            $table->foreignId('company_equipment_id')->nullable()->constrained('company_equipments')->nullOnDelete();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};