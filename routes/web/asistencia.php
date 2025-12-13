<?php

use App\Http\Controllers\AsistenciaController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['web','auth'], 'prefix' => 'asistencias/'], function () {
    Route::get('', [AsistenciaController::class, 'index'])->name('asistencias.index');
    Route::get('datatable', [AsistenciaController::class, 'getAsistenciasAjax'])->name('asistencias.datatable');
});

