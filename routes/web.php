<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\EstudianteController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TutorController;
use App\Http\Controllers\AuditController;
use App\Http\Controllers\DerivacionController;
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
    Route::get('/encuestas', [AdminController::class, 'encuestas'])->name('encuestas.index');
    Route::get('/encuestas/{asignacion}', [AdminController::class, 'nuevaEncuesta'])->name('encuestas.crear');
    Route::post('/encuestas', [AdminController::class, 'guardarEncuesta'])->name('encuestas.guardar');
    Route::get('/configuracion', [AdminController::class, 'configuracion'])->name('config');
    Route::post('/configuracion', [AdminController::class, 'guardarConfig'])->name('config.guardar');
    
    // CUS08: Rutas de reportes
    Route::get('/reportes/ficha/{estudiante}', [AdminController::class, 'fichaIndividualPDF'])->name('reportes.ficha');
    Route::get('/reportes/informe-general', [AdminController::class, 'informeGeneralPDF'])->name('reportes.informe');
    Route::get('/reportes/exportar-excel', [AdminController::class, 'exportarExcel'])->name('reportes.excel');
    Route::get('/reportes/fichas-masivas', [AdminController::class, 'exportarFichasMasivas'])->name('reportes.fichas-masivas');
    
    // CUS10: Rutas de auditoría
    Route::get('/auditoria', [AuditController::class, 'index'])->name('auditoria.index');
    Route::get('/auditoria/{log}', [AuditController::class, 'show'])->name('auditoria.show');
    Route::get('/auditoria/exportar/excel', [AuditController::class, 'exportarExcel'])->name('auditoria.excel');
    
    // CUS06: Rutas de derivaciones (solo para admin)
    Route::get('/derivaciones/estadisticas', [DerivacionController::class, 'estadisticas'])->name('derivaciones.estadisticas');
    Route::put('/derivaciones/{id}/actualizar', [DerivacionController::class, 'actualizar'])->name('derivaciones.actualizar');
});

// Tutor routes
Route::middleware(['auth', 'role:tutor'])->prefix('tutor')->name('tutor.')->group(function () {
    Route::get('/dashboard', [TutorController::class, 'dashboard'])->name('dashboard');
    Route::get('/mis-estudiantes', [TutorController::class, 'misEstudiantes'])->name('estudiantes');
    Route::get('/entrevista/{estudiante}', [TutorController::class, 'nuevaEntrevista'])->name('entrevista');
    Route::post('/guardar-entrevista', [TutorController::class, 'guardarEntrevista'])->name('guardar');
    Route::get('/historial/{estudiante}', [TutorController::class, 'historial'])->name('historial');
    Route::get('/observaciones', [TutorController::class, 'observaciones'])->name('observaciones');
    Route::post('/observaciones/guardar', [TutorController::class, 'guardarObservacion'])->name('observaciones.guardar');
    Route::get('/alertas', [TutorController::class, 'alertas'])->name('alertas');
    
    // CUS06: Derivaciones
    Route::get('/derivaciones', [DerivacionController::class, 'index'])->name('derivaciones');
    Route::get('/derivar/{estudianteId}', [DerivacionController::class, 'crear'])->name('derivar');
    Route::post('/derivaciones/registrar', [DerivacionController::class, 'registrar'])->name('derivaciones.registrar');
    Route::get('/derivaciones/{id}', [DerivacionController::class, 'ver'])->name('derivaciones.ver');
    Route::put('/derivaciones/{id}/actualizar', [DerivacionController::class, 'actualizar'])->name('derivaciones.actualizar');
});

// Estudiante routes
Route::middleware(['auth', 'role:estudiante'])->prefix('estudiante')->name('estudiante.')->group(function () {
    Route::get('/mi-estado', [EstudianteController::class, 'miEstado'])->name('estado');
    Route::get('/notificaciones', [EstudianteController::class, 'notificaciones'])->name('notificaciones');
    Route::post('/notificaciones/leer', [EstudianteController::class, 'marcarLeido'])->name('notificaciones.leer');
});

// CUS06: Rutas compartidas de derivaciones (admin y tutor)
Route::middleware(['auth', 'role:admin,tutor'])->group(function () {
    Route::get('/derivaciones', [DerivacionController::class, 'index'])->name('derivaciones.index');
    Route::get('/derivaciones/{id}', [DerivacionController::class, 'ver'])->name('derivaciones.ver');
    Route::get('/derivar/{estudianteId}', [DerivacionController::class, 'crear'])->name('derivaciones.crear');
    Route::post('/derivaciones/registrar', [DerivacionController::class, 'registrar'])->name('derivaciones.registrar');
    Route::get('/derivaciones/estadisticas', [DerivacionController::class, 'estadisticas'])->name('derivaciones.estadisticas');
    Route::put('/derivaciones/{id}/actualizar', [DerivacionController::class, 'actualizar'])->name('derivaciones.actualizar');
});

require __DIR__ . '/auth.php';
