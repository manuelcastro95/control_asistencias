<?php

use App\Http\Controllers\AlumnoController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['web','auth'], 'prefix' => 'alumnos/'], function () {
    Route::get('', [AlumnoController::class, 'index'])->name('alumnos.index');
    Route::post('store', [AlumnoController::class, 'store'])->name('alumnos.store');
    Route::get('{alumno}/show', [AlumnoController::class, 'show'])->name('alumnos.show');
    Route::put('{alumno}',[AlumnoController::class,'update'])->name('alumnos.update');
    Route::delete('{alumno}/destroy', [AlumnoController::class, 'destroy'])->name('alumnos.destroy');

    Route::post('save-record', [AlumnoController::class, 'save_record'])->name('save.record');
});

