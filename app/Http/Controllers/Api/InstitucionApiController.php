<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\InstitucionResource;
use App\Models\Institucion;
use Illuminate\Http\JsonResponse;

class InstitucionApiController extends Controller
{
    /**
     * Listar todas las instituciones
     */
    public function index(): JsonResponse
    {
        $instituciones = Institucion::with('sedes.grados')
            ->where('activo', true)
            ->get();

        return response()->json([
            'success' => true,
            'data' => InstitucionResource::collection($instituciones)
        ]);
    }

    /**
     * Obtener una institución específica
     */
    public function show($id): JsonResponse
    {
        $institucion = Institucion::with(['sedes.grados', 'sedes.grados.alumnos' => function($q) {
            $q->where('activo', true);
        }])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => new InstitucionResource($institucion)
        ]);
    }
}

