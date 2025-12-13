<?php

use App\Http\Controllers\GradoController;
use App\Http\Controllers\InstitucionController;
use App\Http\Controllers\SedeController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['web', 'auth'], 'prefix' => 'configuracion/'], function () {
    // Instituciones
    Route::get('instituciones', [InstitucionController::class, 'index'])->name('instituciones.index');
    Route::post('instituciones', [InstitucionController::class, 'store'])->name('instituciones.store');
    Route::get('instituciones/{institucion}', [InstitucionController::class, 'show'])->name('instituciones.show');
    Route::put('instituciones/{institucion}', [InstitucionController::class, 'update'])->name('instituciones.update');
    Route::delete('instituciones/{institucion}', [InstitucionController::class, 'destroy'])->name('instituciones.destroy');

    // Sedes
    Route::get('sedes', [SedeController::class, 'index'])->name('sedes.index');
    Route::post('sedes', [SedeController::class, 'store'])->name('sedes.store');
    Route::get('sedes/{sede}', [SedeController::class, 'show'])->name('sedes.show');
    Route::put('sedes/{sede}', [SedeController::class, 'update'])->name('sedes.update');
    Route::delete('sedes/{sede}', [SedeController::class, 'destroy'])->name('sedes.destroy');

    // Grados
    Route::get('grados', [GradoController::class, 'index'])->name('grados.index');
    Route::post('grados', [GradoController::class, 'store'])->name('grados.store');
    Route::get('grados/{grado}', [GradoController::class, 'show'])->name('grados.show');
    Route::put('grados/{grado}', [GradoController::class, 'update'])->name('grados.update');
    Route::delete('grados/{grado}', [GradoController::class, 'destroy'])->name('grados.destroy');
});

