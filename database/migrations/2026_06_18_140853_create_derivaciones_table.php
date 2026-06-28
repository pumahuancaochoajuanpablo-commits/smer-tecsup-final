<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('derivaciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('estudiante_id');
            $table->unsignedBigInteger('tutor_id');
            $table->string('motivo');
            $table->text('descripcion');
            $table->enum('estado', ['pendiente', 'derivado', 'rechazado', 'completado'])->default('pendiente');
            $table->string('responsable_bienestar')->nullable();
            $table->json('observaciones')->nullable();
            $table->timestamp('fecha_derivacion')->useCurrent();
            $table->timestamp('fecha_respuesta')->nullable();
            $table->timestamps();

            $table->foreign('estudiante_id')->references('id')->on('estudiantes')->onDelete('cascade');
            $table->foreign('tutor_id')->references('id')->on('tutores')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('derivaciones');
    }
};
