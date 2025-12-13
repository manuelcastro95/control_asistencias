<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AsistenciaResource;
use App\Models\Asistencia;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AlumnoPerfilApiController extends Controller
{
    /**
     * Obtener información del estudiante autenticado
     */
    public function perfil(Request $request): JsonResponse
    {
        $alumno = $request->user();
        $alumno->load('grado.sede.institucion');

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $alumno->id,
                'codigo' => $alumno->codigo,
                'nombres' => $alumno->nombres,
                'apellidos' => $alumno->apellidos,
                'nombre_completo' => $alumno->full_name,
                'email' => $alumno->email,
                'telefono' => $alumno->telefono,
                'fecha_nacimiento' => $alumno->fecha_nacimiento?->format('Y-m-d'),
                'edad' => $alumno->edad,
                'genero' => $alumno->genero,
                'documento_identidad' => $alumno->documento_identidad,
                'direccion' => $alumno->direccion,
                'nombre_acudiente' => $alumno->nombre_acudiente,
                'telefono_acudiente' => $alumno->telefono_acudiente,
                'observaciones' => $alumno->observaciones,
                'grado' => $alumno->grado ? [
                    'id' => $alumno->grado->id,
                    'nombre' => $alumno->grado->nombre,
                    'codigo' => $alumno->grado->codigo,
                ] : null,
                'sede' => $alumno->grado && $alumno->grado->sede ? [
                    'id' => $alumno->grado->sede->id,
                    'nombre' => $alumno->grado->sede->nombre,
                    'codigo' => $alumno->grado->sede->codigo,
                ] : null,
                'institucion' => $alumno->grado && $alumno->grado->sede && $alumno->grado->sede->institucion ? [
                    'id' => $alumno->grado->sede->institucion->id,
                    'nombre' => $alumno->grado->sede->institucion->nombre,
                ] : null,
                'activo' => $alumno->activo,
            ]
        ]);
    }

    /**
     * Obtener QR del estudiante autenticado
     */
    public function qr(Request $request): JsonResponse
    {
        $alumno = $request->user();

        return response()->json([
            'success' => true,
            'data' => [
                'codigo' => $alumno->codigo,
                'qr_base64' => $alumno->qr_base,
                'qr_completo_base64' => $alumno->qr_completo,
                'nombre_completo' => $alumno->full_name,
                'grado' => $alumno->grado ? $alumno->grado->nombre : null,
            ]
        ]);
    }

    /**
     * Obtener asistencias del estudiante autenticado
     */
    public function asistencias(Request $request): JsonResponse
    {
        $alumno = $request->user();

        $query = Asistencia::where('alumno_id', $alumno->id);

        // Filtros opcionales
        if ($request->has('fecha_inicio')) {
            $query->whereDate('fecha', '>=', $request->fecha_inicio);
        }

        if ($request->has('fecha_fin')) {
            $query->whereDate('fecha', '<=', $request->fecha_fin);
        }

        $asistencias = $query->orderBy('fecha', 'desc')
            ->orderBy('hora', 'desc')
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => AsistenciaResource::collection($asistencias),
            'meta' => [
                'current_page' => $asistencias->currentPage(),
                'last_page' => $asistencias->lastPage(),
                'per_page' => $asistencias->perPage(),
                'total' => $asistencias->total(),
            ]
        ]);
    }

    /**
     * Estadísticas del estudiante autenticado
     */
    public function estadisticas(Request $request): JsonResponse
    {
        $alumno = $request->user();

        $totalAsistencias = Asistencia::where('alumno_id', $alumno->id)->count();
        $asistenciasMes = Asistencia::where('alumno_id', $alumno->id)
            ->whereMonth('fecha', now()->month)
            ->whereYear('fecha', now()->year)
            ->count();
        
        $asistenciasSemana = Asistencia::where('alumno_id', $alumno->id)
            ->whereBetween('fecha', [now()->startOfWeek(), now()->endOfWeek()])
            ->count();

        $ultimaAsistencia = Asistencia::where('alumno_id', $alumno->id)
            ->orderBy('fecha', 'desc')
            ->orderBy('hora', 'desc')
            ->first();

        return response()->json([
            'success' => true,
            'data' => [
                'total_asistencias' => $totalAsistencias,
                'asistencias_mes' => $asistenciasMes,
                'asistencias_semana' => $asistenciasSemana,
                'ultima_asistencia' => $ultimaAsistencia ? new AsistenciaResource($ultimaAsistencia) : null,
            ]
        ]);
    }
}

