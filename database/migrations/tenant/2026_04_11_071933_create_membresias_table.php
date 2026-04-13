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
            Schema::create('membresias', function (Blueprint $table) {
                $table->id();
                $table->foreignId('socio_id')->constrained('socios')->cascadeOnDelete();
                $table->foreignId('plan_id')->constrained('planes')->cascadeOnDelete();
                $table->date('fecha_inicio');
                $table->date('fecha_fin');
                $table->enum('estado', ['activa','vencida','cancelada'])->default('activa');
                $table->timestamps();
            });
        }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('membresias');
    }
};
