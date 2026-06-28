<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('estudiantes', function (Blueprint $table) {
            $table->string('ciclo', 20)->nullable()->after('carrera');
        });

        Schema::table('asignaciones', function (Blueprint $table) {
            $table->enum('estado', ['activa', 'completada', 'cancelada'])->default('activa')->after('fecha_inicio');
        });

        Schema::table('roles', function (Blueprint $table) {
            $table->text('descripcion')->nullable()->after('nombre');
        });

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
