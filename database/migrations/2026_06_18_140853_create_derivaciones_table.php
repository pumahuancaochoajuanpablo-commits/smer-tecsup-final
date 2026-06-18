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
        Schema::create('derivaciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('estudiante_id');
            $table->unsignedBigInteger('tutor_id');
            $table->string('motivo'); // Riesgo alto, problema conductual, etc.
            $table->text('descripcion'); // Descripción detallada de la derivación
            $table->enum('estado', ['pendiente', 'derivado', 'rechazado', 'completado'])->default('pendiente');
            $table->string('responsable_bienestar')->nullable(); // Persona encargada en bienestar
            $table->json('observaciones')->nullable(); // Seguimiento de la derivación
            $table->timestamp('fecha_derivacion')->useCurrent();
            $table->timestamp('fecha_respuesta')->nullable(); // Cuando bienestar responde
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('estudiante_id')->references('id')->on('estudiantes')->onDelete('cascade');
            $table->foreign('tutor_id')->references('id')->on('tutores')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('derivaciones');
    }
};
