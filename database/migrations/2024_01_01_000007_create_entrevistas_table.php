<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('entrevistas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asignacion_id')->constrained('asignaciones')->onDelete('cascade');
            $table->date('fecha');
            $table->integer('acad_2');
            $table->integer('emoc_2');
            $table->integer('soc_2');
            $table->integer('econ_2');
            $table->integer('fam_2');
            $table->integer('salud_2');
            $table->decimal('puntaje_total', 5, 2)->nullable();
            $table->enum('nivel_riesgo', ['bajo', 'medio', 'alto'])->default('bajo');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entrevistas');
    }
};
