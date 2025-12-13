<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AlumnoResource;
use App\Models\Alumno;
use App\Models\Asistencia;
use App\Services\AlumnoService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AlumnoApiController extends Controller
{
    protected $alumnoService;

    public function __construct(AlumnoService $alumnoService)
    {
        $this->alumnoService = $alumnoService;
    }

    /**
     * Listar todos los alumnos
     */
    public function index(Request $request): JsonResponse
    {
        $query = Alumno::with(['grado.sede.institucion']);

        // Filtros
        if ($request->has('grado_id')) {
            $query->where('grado_id', $request->grado_id);
        }

        if ($request->has('activo')) {
            $query->where('activo', $request->activo);
        }

        if ($request->has('buscar')) {
            $buscar = $request->buscar;
            $query->where(function($q) use ($buscar) {
                $q->where('codigo', 'like', "%{$buscar}%")
                  ->orWhere('nombres', 'like', "%{$buscar}%")
                  ->orWhere('apellidos', 'like', "%{$buscar}%")
                  ->orWhere('documento_identidad', 'like', "%{$buscar}%");
            });
        }

        $perPage = $request->get('per_page', 15);
        $alumnos = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => AlumnoResource::collection($alumnos),
            'pagination' => [
                'current_page' => $alumnos->currentPage(),
                'last_page' => $alumnos->lastPage(),
                'per_page' => $alumnos->perPage(),
                'total' => $alumnos->total(),
            ]
        ]);
    }

    /**
     * Obtener un alumno específico
     */
    public function show($id): JsonResponse
    {
        $alumno = Alumno::with(['grado.sede.institucion', 'asistencias' => function($q) {
            $q->orderBy('fecha', 'desc')->limit(10);
        }])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => new AlumnoResource($alumno)
        ]);
    }

    /**
     * Buscar alumno por código (para escáner QR)
     */
    public function buscarPorCodigo(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'codigo' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Código requerido',
                'errors' => $validator->errors()
            ], 422);
        }

        $alumno = Alumno::with('grado.sede.institucion')
            ->where('codigo', $request->codigo)
            ->where('activo', true)
            ->first();

        if (!$alumno) {
            return response()->json([
                'success' => false,
                'message' => 'Alumno no encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new AlumnoResource($alumno)
        ]);
    }

    /**
     * Registrar asistencia desde API
     */
    public function registrarAsistencia(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'codigo' => 'required|string|exists:alumnos,codigo'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->alumnoService->registrarAsistencia($request->codigo);

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'message' => $result['msg'],
                'data' => [
                    'alumno' => new AlumnoResource(Alumno::find($result['asistencia']->alumno_id)),
                    'asistencia' => [
                        'id' => $result['asistencia']->id,
                        'fecha' => $result['asistencia']->fecha,
                        'hora' => $result['asistencia']->hora,
                    ]
                ]
            ], 201);
        }

        return response()->json([
            'success' => false,
            'message' => $result['msg'],
            'level' => $result['level']
        ], $result['level'] === 'error' ? 400 : 200);
    }

    /**
     * Obtener asistencias de un alumno
     */
    public function asistencias($id, Request $request): JsonResponse
    {
        $alumno = Alumno::findOrFail($id);
        
        $query = Asistencia::where('alumno_id', $alumno->id);

        if ($request->has('fecha_inicio')) {
            $query->whereDate('fecha', '>=', $request->fecha_inicio);
        }

        if ($request->has('fecha_fin')) {
            $query->whereDate('fecha', '<=', $request->fecha_fin);
        }

        $asistencias = $query->orderBy('fecha', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => [
                'alumno' => new AlumnoResource($alumno),
                'asistencias' => \App\Http\Resources\AsistenciaResource::collection($asistencias),
                'total' => $asistencias->count(),
                'asistencias_mes' => Asistencia::where('alumno_id', $alumno->id)
                    ->whereMonth('fecha', now()->month)
                    ->whereYear('fecha', now()->year)
                    ->count(),
            ]
        ]);
    }

    /**
     * Estadísticas del alumno
     */
    public function estadisticas($id): JsonResponse
    {
        $alumno = Alumno::withCount('asistencias')->findOrFail($id);

        $asistenciasMes = Asistencia::where('alumno_id', $alumno->id)
            ->whereMonth('fecha', now()->month)
            ->whereYear('fecha', now()->year)
            ->count();

        $asistenciasSemana = Asistencia::where('alumno_id', $alumno->id)
            ->whereBetween('fecha', [now()->startOfWeek(), now()->endOfWeek()])
            ->count();

        $ultimaAsistencia = Asistencia::where('alumno_id', $alumno->id)
            ->orderBy('fecha', 'desc')
            ->first();

        return response()->json([
            'success' => true,
            'data' => [
                'alumno' => new AlumnoResource($alumno),
                'total_asistencias' => $alumno->asistencias_count,
                'asistencias_mes' => $asistenciasMes,
                'asistencias_semana' => $asistenciasSemana,
                'ultima_asistencia' => $ultimaAsistencia ? $ultimaAsistencia->fecha : null,
                'porcentaje_mes' => now()->day > 0 ? round(($asistenciasMes / now()->day) * 100, 2) : 0,
            ]
        ]);
    }
}

