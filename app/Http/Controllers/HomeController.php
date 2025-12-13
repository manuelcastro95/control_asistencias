<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $asistenciaService = app(\App\Services\AsistenciaService::class);
        $estadisticas = $asistenciaService->getEstadisticas();
        
        // Top 5 alumnos con más asistencias
        $topAlumnos = \App\Models\Alumno::withCount('asistencias')
            ->orderBy('asistencias_count', 'desc')
            ->limit(5)
            ->get();
        
        return view('home', [
            'totalAlumnos' => $estadisticas['total_alumnos'],
            'totalAsistencias' => $estadisticas['total_asistencias'],
            'asistenciasHoy' => $estadisticas['asistencias_hoy'],
            'porcentajeHoy' => $estadisticas['porcentaje_hoy'],
            'asistenciasUltimos7Dias' => $estadisticas['asistencias_por_dia'],
            'topAlumnos' => $topAlumnos
        ]);
    }
}
