<?php

namespace App\Http\Controllers;

use App\Models\Institucion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InstitucionController extends Controller
{
    public function index()
    {
        $instituciones = Institucion::with('sedes')->get();
        return view('configuracion.instituciones.index', compact('instituciones'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'nit' => 'nullable|string|max:50',
            'direccion' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
        ]);

        try {
            $institucion = Institucion::create($request->all());
            
            Log::info('Institución creada', [
                'institucion_id' => $institucion->id,
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'res' => 'ok',
                'message' => 'Institución creada correctamente',
                'institucion' => $institucion
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'res' => 'error',
                'message' => 'Error al crear la institución: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show(Institucion $institucion)
    {
        return response()->json($institucion);
    }

    public function update(Request $request, Institucion $institucion)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'nit' => 'nullable|string|max:50',
            'direccion' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
        ]);

        try {
            $institucion->update($request->all());
            
            Log::info('Institución actualizada', [
                'institucion_id' => $institucion->id,
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'res' => 'ok',
                'message' => 'Institución actualizada correctamente',
                'institucion' => $institucion
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'res' => 'error',
                'message' => 'Error al actualizar la institución: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Institucion $institucion)
    {
        try {
            $institucion->delete();
            
            Log::info('Institución eliminada', [
                'institucion_id' => $institucion->id,
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'res' => 'ok',
                'message' => 'Institución eliminada correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'res' => 'error',
                'message' => 'Error al eliminar la institución: ' . $e->getMessage()
            ], 500);
        }
    }
}

