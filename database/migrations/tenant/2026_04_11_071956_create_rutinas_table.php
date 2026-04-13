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
            Schema::create('rutinas', function (Blueprint $table) {
                $table->id();
                $table->foreignId('socio_id')->constrained()->cascadeOnDelete();
                $table->string('nombre');
                $table->enum('dia', [
                    'Lunes','Martes','Miércoles',
                    'Jueves','Viernes','Sábado','Domingo'
                ])->nullable();
                $table->json('ejercicios');   // [{nombre, series, reps, peso_kg}]
                $table->boolean('activo')->default(true);
                $table->timestamps();
            });
        }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rutinas');
    }
};
