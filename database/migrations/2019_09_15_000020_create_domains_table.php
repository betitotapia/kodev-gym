<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('domains', function (Blueprint $table) {
        $table->id();
        $table->string('domain', 191)->unique();
        $table->string('tenant_id');                          // ← string, no foreignId
        $table->foreign('tenant_id')
              ->references('id')
              ->on('tenants')
              ->cascadeOnDelete();
        $table->timestamps();
    });
}
    public function down(): void
    {
        Schema::dropIfExists('domains');
    }
};