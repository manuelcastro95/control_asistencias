<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AsistenciaService;
use Illuminate\Http\JsonResponse;

class DashboardApiController extends Controller
{
    /**
     * Obtener estadísticas del dashboard
     */
    public function estadisticas(): JsonResponse
    {
        $asistenciaService = app(AsistenciaService::class);
        $estadisticas = $asistenciaService->getEstadisticas();

        // Top 5 alumnos
        $topAlumnos = \App\Models\Alumno::withCount('asistencias')
            ->orderBy('asistencias_count', 'desc')
            ->limit(5)
            ->get()
            ->map(function($alumno) {
                return [
                    'id' => $alumno->id,
                    'codigo' => $alumno->codigo,
                    'nombre_completo' => $alumno->full_name,
                    'total_asistencias' => $alumno->asistencias_count,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => [
                'total_alumnos' => $estadisticas['total_alumnos'],
                'total_asistencias' => $estadisticas['total_asistencias'],
                'asistencias_hoy' => $estadisticas['asistencias_hoy'],
                'porcentaje_hoy' => $estadisticas['porcentaje_hoy'],
                'asistencias_por_dia' => $estadisticas['asistencias_por_dia'],
                'top_alumnos' => $topAlumnos,
            ]
        ]);
    }
}

