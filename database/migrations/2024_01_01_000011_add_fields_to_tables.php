<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Agregar campo ciclo a estudiantes
        Schema::table('estudiantes', function (Blueprint $table) {
            $table->string('ciclo', 20)->nullable()->after('carrera');
        });

        // Agregar campo estado a asignaciones
        Schema::table('asignaciones', function (Blueprint $table) {
            $table->enum('estado', ['activa', 'completada', 'cancelada'])->default('activa')->after('fecha_inicio');
        });

        // Agregar campo descripcion a roles
        Schema::table('roles', function (Blueprint $table) {
            $table->text('descripcion')->nullable()->after('nombre');
        });

        // Agregar campo token_jwt a sessions
        Schema::table('sessions', function (Blueprint $table) {
            $table->text('token_jwt')->nullable()->after('payload');
        });
    }

    public function down(): void
    {
        Schema::table('estudiantes', function (Blueprint $table) {
            $table->dropColumn('ciclo');
        });

        Schema::table('asignaciones', function (Blueprint $table) {
            $table->dropColumn('estado');
        });

        Schema::table('roles', function (Blueprint $table) {
            $table->dropColumn('descripcion');
        });

        Schema::table('sessions', function (Blueprint $table) {
            $table->dropColumn('token_jwt');
        });
    }
};
