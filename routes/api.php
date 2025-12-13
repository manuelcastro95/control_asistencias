<?php

use App\Http\Controllers\Api\AlumnoApiController;
use App\Http\Controllers\Api\AlumnoPerfilApiController;
use App\Http\Controllers\Api\AsistenciaApiController;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\AuthAlumnoApiController;
use App\Http\Controllers\Api\GradoApiController;
use App\Http\Controllers\Api\InstitucionApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// ============================================
// RUTAS PÚBLICAS (SIN AUTENTICACIÓN)
// ============================================

// Login de administrador
Route::post('/admin/login', [AuthApiController::class, 'login'])->name('api.admin.login');

// Login de estudiante
Route::post('/alumno/login', [AuthAlumnoApiController::class, 'login'])->name('api.alumno.login');

// ============================================
// RUTAS PROTEGIDAS - ADMINISTRADOR
// ============================================
Route::middleware('auth:sanctum')->group(function () {
    // Autenticación de administrador
    Route::post('/admin/logout', [AuthApiController::class, 'logout'])->name('api.admin.logout');
    Route::get('/admin/me', [AuthApiController::class, 'me'])->name('api.admin.me');

    // Instituciones (solo admin)
    Route::get('/admin/instituciones', [InstitucionApiController::class, 'index'])->name('api.admin.instituciones.index');
    Route::get('/admin/instituciones/{id}', [InstitucionApiController::class, 'show'])->name('api.admin.instituciones.show');

    // Grados (solo admin)
    Route::get('/admin/grados', [GradoApiController::class, 'index'])->name('api.admin.grados.index');
    Route::get('/admin/grados/{id}', [GradoApiController::class, 'show'])->name('api.admin.grados.show');
    Route::get('/admin/grados/{id}/alumnos', [GradoApiController::class, 'alumnos'])->name('api.admin.grados.alumnos');

    // Alumnos (solo admin)
    Route::get('/admin/alumnos', [AlumnoApiController::class, 'index'])->name('api.admin.alumnos.index');
    Route::get('/admin/alumnos/{id}', [AlumnoApiController::class, 'show'])->name('api.admin.alumnos.show');
    Route::get('/admin/alumnos/buscar/codigo', [AlumnoApiController::class, 'buscarPorCodigo'])->name('api.admin.alumnos.buscar');
    Route::get('/admin/alumnos/{id}/asistencias', [AlumnoApiController::class, 'asistencias'])->name('api.admin.alumnos.asistencias');
    Route::get('/admin/alumnos/{id}/estadisticas', [AlumnoApiController::class, 'estadisticas'])->name('api.admin.alumnos.estadisticas');
    Route::post('/admin/alumnos/registrar-asistencia', [AlumnoApiController::class, 'registrarAsistencia'])->name('api.admin.alumnos.registrar-asistencia');

    // Asistencias (solo admin)
    Route::get('/admin/asistencias', [AsistenciaApiController::class, 'index'])->name('api.admin.asistencias.index');
    Route::get('/admin/asistencias/{id}', [AsistenciaApiController::class, 'show'])->name('api.admin.asistencias.show');
    Route::get('/admin/asistencias/estadisticas', [AsistenciaApiController::class, 'estadisticas'])->name('api.admin.asistencias.estadisticas');

    // Dashboard (solo admin)
    Route::get('/admin/dashboard/estadisticas', [\App\Http\Controllers\Api\DashboardApiController::class, 'estadisticas'])->name('api.admin.dashboard.estadisticas');
});

// ============================================
// RUTAS PROTEGIDAS - ESTUDIANTE
// ============================================
Route::middleware('auth:sanctum')->group(function () {
    // Autenticación de estudiante
    Route::post('/alumno/logout', [AuthAlumnoApiController::class, 'logout'])->name('api.alumno.logout');
    Route::get('/alumno/me', [AuthAlumnoApiController::class, 'me'])->name('api.alumno.me');

    // Perfil del estudiante (solo puede ver su propia información)
    Route::get('/alumno/perfil', [AlumnoPerfilApiController::class, 'perfil'])->name('api.alumno.perfil');
    Route::get('/alumno/qr', [AlumnoPerfilApiController::class, 'qr'])->name('api.alumno.qr');
    Route::get('/alumno/asistencias', [AlumnoPerfilApiController::class, 'asistencias'])->name('api.alumno.asistencias');
    Route::get('/alumno/estadisticas', [AlumnoPerfilApiController::class, 'estadisticas'])->name('api.alumno.estadisticas');
});
