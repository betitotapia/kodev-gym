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
       Schema::create('socios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_completo');
            $table->date('fecha_nacimiento')->nullable();
            $table->integer('edad')->nullable();
            $table->enum('genero', ['Hombre', 'Mujer', 'Otro'])->nullable();
            $table->string('domicilio')->nullable();
            $table->string('telefono')->nullable();
            $table->string('email')->nullable();
            $table->string('identificacion')->nullable();
            $table->string('fotografia')->nullable();   // ruta en S3
            $table->text('firma')->nullable();           // ruta en S3
            $table->enum('presion_arterial', ['Alta','Baja','Normal','Desconozco'])->nullable();
            $table->boolean('afeccion_cardiaca')->default(false);
            $table->string('detalle_cardiaco')->nullable();
            $table->boolean('afeccion_respiratoria')->default(false);
            $table->string('detalle_respiratorio')->nullable();
            $table->boolean('alergia')->default(false);
            $table->string('detalle_alergia')->nullable();
            $table->boolean('lesion')->default(false);
            $table->string('detalle_lesion')->nullable();
            $table->string('contacto_emergencia')->nullable();
            $table->string('telefono_emergencia')->nullable();
            $table->enum('tipo_entrenamiento', ['GYM','CROSSFIT','COMBINADO'])->default('GYM');
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('socios');
    }
};
