<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('accion'); // create, update, delete, view, download
            $table->string('modelo'); // Entrevista, Estudiante, etc.
            $table->unsignedBigInteger('modelo_id')->nullable();
            $table->text('detalles')->nullable(); // JSON con cambios realizados
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
            $table->index(['modelo', 'accion']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
