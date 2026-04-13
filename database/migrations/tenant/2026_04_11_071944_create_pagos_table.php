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
            Schema::create('pagos', function (Blueprint $table) {
                $table->id();
                $table->foreignId('socio_id')->constrained()->cascadeOnDelete();
                $table->foreignId('membresia_id')->nullable()->constrained()->nullOnDelete();
                $table->decimal('monto', 10, 2);
                $table->enum('metodo', ['efectivo','transferencia','tarjeta'])->default('efectivo');
                $table->string('referencia')->nullable();  // número de transferencia, etc.
                $table->string('recibo_pdf')->nullable();  // ruta en S3
                $table->date('fecha_pago');
                $table->text('notas')->nullable();
                $table->timestamps();
            });
        }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
