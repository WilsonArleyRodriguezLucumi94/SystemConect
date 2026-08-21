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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('description');                       // Descripción del gasto (ej. Compra de fibra, Pago de luz)
            $table->decimal('amount', 10, 2);                    // Monto del gasto
            $table->string('category')->nullable();             // Categoría: Mantenimiento, Equipos, Nómina, Servicios, etc.
            $table->date('expense_date');                       // Fecha en que se realizó el gasto
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // Usuario que registró el gasto
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
