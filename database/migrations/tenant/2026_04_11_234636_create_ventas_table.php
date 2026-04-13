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
            Schema::create('ventas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('socio_id')->nullable()->constrained('socios')->nullOnDelete();
            $table->foreignId('membresia_id')->nullable()->constrained('membresias')->nullOnDelete();
            $table->enum('tipo', ['producto', 'membresia', 'mixta'])->default('producto');
            $table->json('items'); // [{producto_id, nombre, qty, precio_unitario, subtotal}]
            $table->decimal('subtotal', 10, 2);
            $table->decimal('descuento', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->enum('metodo_pago', ['efectivo','transferencia','tarjeta'])->default('efectivo');
            $table->string('referencia')->nullable();
            $table->text('notas')->nullable();
            $table->timestamp('fecha_venta')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ventas');
    }
};
