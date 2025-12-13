<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\GradoResource;
use App\Models\Grado;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GradoApiController extends Controller
{
    /**
     * Listar todos los grados
     */
    public function index(Request $request): JsonResponse
    {
        $query = Grado::with(['sede.institucion'])->where('activo', true);

        if ($request->has('sede_id')) {
            $query->where('sede_id', $request->sede_id);
        }

        if ($request->has('institucion_id')) {
            $query->whereHas('sede', function($q) use ($request) {
                $q->where('institucion_id', $request->institucion_id);
            });
        }

        $grados = $query->orderBy('orden')->get();

        return response()->json([
            'success' => true,
            'data' => GradoResource::collection($grados)
        ]);
    }

    /**
     * Obtener un grado específico
     */
    public function show($id): JsonResponse
    {
        $grado = Grado::with(['sede.institucion', 'alumnos' => function($q) {
            $q->where('activo', true);
        }])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => new GradoResource($grado->loadCount('alumnos'))
        ]);
    }

    /**
     * Obtener alumnos de un grado
     */
    public function alumnos($id, Request $request): JsonResponse
    {
        $grado = Grado::findOrFail($id);
        
        $query = $grado->alumnos()->with('grado.sede.institucion')->where('activo', true);

        if ($request->has('buscar')) {
            $buscar = $request->buscar;
            $query->where(function($q) use ($buscar) {
                $q->where('codigo', 'like', "%{$buscar}%")
                  ->orWhere('nombres', 'like', "%{$buscar}%")
                  ->orWhere('apellidos', 'like', "%{$buscar}%");
            });
        }

        $perPage = $request->get('per_page', 15);
        $alumnos = $query->orderBy('nombres')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => [
                'grado' => new GradoResource($grado),
                'alumnos' => \App\Http\Resources\AlumnoResource::collection($alumnos),
                'pagination' => [
                    'current_page' => $alumnos->currentPage(),
                    'last_page' => $alumnos->lastPage(),
                    'per_page' => $alumnos->perPage(),
                    'total' => $alumnos->total(),
                ]
            ]
        ]);
    }
}

