<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parametros_riesgo', function (Blueprint $table) {
            $table->id();
            $table->string('indicador', 20)->unique();
            $table->decimal('peso', 5, 2);
            $table->integer('umbral_bajo');
            $table->integer('umbral_medio');
            $table->integer('umbral_alto');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parametros_riesgo');
    }
};
