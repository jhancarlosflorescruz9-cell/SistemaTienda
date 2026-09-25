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
        Schema::create('cuenta_bancarias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('banco_id')->constrained('bancos')->cascadeOnDelete();
            $table->foreignId('moneda_id')->constrained('monedas')->cascadeOnDelete();
            $table->string('descripcion', 100);
            $table->string('numero', 50);
            $table->string('cci', 50);
            $table->decimal('saldo_inicial', 15, 2)->default(0);
            $table->enum('mostrar_comprobante', ['Si', 'No'])->default('No');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cuenta_bancarias');
    }
};
