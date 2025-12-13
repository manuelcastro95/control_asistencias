<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AsistenciaResource;
use App\Models\Asistencia;
use App\Services\AsistenciaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AsistenciaApiController extends Controller
{
    protected $asistenciaService;

    public function __construct(AsistenciaService $asistenciaService)
    {
        $this->asistenciaService = $asistenciaService;
    }

    /**
     * Listar asistencias
     */
    public function index(Request $request): JsonResponse
    {
        $filters = [
            'fecha_inicio' => $request->get('fecha_inicio'),
            'fecha_fin' => $request->get('fecha_fin'),
            'alumno_codigo' => $request->get('alumno_codigo'),
        ];

        $asistencias = $this->asistenciaService->getAsistencias($filters);

        $perPage = $request->get('per_page', 15);
        $page = $request->get('page', 1);
        $offset = ($page - 1) * $perPage;
        $paginated = array_slice($asistencias->toArray(), $offset, $perPage);

        return response()->json([
            'success' => true,
            'data' => AsistenciaResource::collection(collect($paginated)),
            'pagination' => [
                'current_page' => $page,
                'per_page' => $perPage,
                'total' => $asistencias->count(),
                'last_page' => ceil($asistencias->count() / $perPage),
            ]
        ]);
    }

    /**
     * Obtener estadísticas de asistencias
     */
    public function estadisticas(Request $request): JsonResponse
    {
        $estadisticas = $this->asistenciaService->getEstadisticas(
            $request->get('fecha_inicio'),
            $request->get('fecha_fin')
        );

        return response()->json([
            'success' => true,
            'data' => $estadisticas
        ]);
    }

    /**
     * Obtener una asistencia específica
     */
    public function show($id): JsonResponse
    {
        $asistencia = Asistencia::with('alumno.grado.sede.institucion')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => new AsistenciaResource($asistencia)
        ]);
    }
}

