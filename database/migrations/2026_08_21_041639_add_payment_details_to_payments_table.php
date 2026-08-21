<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->string('payment_method')->nullable()->after('amount'); // Efectivo, Nequi, Daviplata, Bancolombia, etc.
            $table->string('proof_image')->nullable()->after('payment_method'); // Ruta de la imagen cargada
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['payment_method', 'proof_image']);
        });
    }
};