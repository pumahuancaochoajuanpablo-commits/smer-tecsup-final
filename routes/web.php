<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\EstudianteController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TutorController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/tutores', [AdminController::class, 'tutores'])->name('tutores');
    Route::post('/tutores', [AdminController::class, 'guardarTutor'])->name('tutores.guardar');
    Route::get('/estudiantes/importar', [AdminController::class, 'importarForm'])->name('importar');
    Route::post('/estudiantes/importar', [AdminController::class, 'importarCSV'])->name('importar.csv');
    Route::get('/asignaciones', [AdminController::class, 'asignacionesForm'])->name('asignaciones');
    Route::post('/asignaciones', [AdminController::class, 'asignarTutoria'])->name('asignaciones.guardar');
    Route::get('/configuracion', [AdminController::class, 'configuracion'])->name('config');
    Route::post('/configuracion', [AdminController::class, 'guardarConfig'])->name('config.guardar');
});

// Tutor routes
Route::middleware(['auth', 'role:tutor'])->prefix('tutor')->name('tutor.')->group(function () {
    Route::get('/mis-estudiantes', [TutorController::class, 'misEstudiantes'])->name('estudiantes');
    Route::get('/entrevista/{estudiante}', [TutorController::class, 'nuevaEntrevista'])->name('entrevista');
    Route::post('/guardar-entrevista', [TutorController::class, 'guardarEntrevista'])->name('guardar');
    Route::get('/historial/{estudiante}', [TutorController::class, 'historial'])->name('historial');
});

// Estudiante routes
Route::middleware(['auth', 'role:estudiante'])->prefix('estudiante')->name('estudiante.')->group(function () {
    Route::get('/mi-estado', [EstudianteController::class, 'miEstado'])->name('estado');
    Route::get('/notificaciones', [EstudianteController::class, 'notificaciones'])->name('notificaciones');
    Route::post('/notificaciones/leer', [EstudianteController::class, 'marcarLeido'])->name('notificaciones.leer');
});

require __DIR__ . '/auth.php';
