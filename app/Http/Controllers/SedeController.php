<?php

namespace App\Http\Controllers;

use App\Models\Institucion;
use App\Models\Sede;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SedeController extends Controller
{
    public function index(Request $request)
    {
        $query = Sede::with('institucion');
        
        if ($request->has('institucion_id')) {
            $query->where('institucion_id', $request->institucion_id);
        }
        
        $sedes = $query->get();
        $instituciones = Institucion::where('activo', true)->get();
        
        return view('configuracion.sedes.index', compact('sedes', 'instituciones'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'institucion_id' => 'required|exists:instituciones,id',
            'nombre' => 'required|string|max:255',
            'codigo' => 'nullable|string|max:50',
            'direccion' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
        ]);

        try {
            $sede = Sede::create($request->all());
            
            Log::info('Sede creada', [
                'sede_id' => $sede->id,
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'res' => 'ok',
                'message' => 'Sede creada correctamente',
                'sede' => $sede->load('institucion')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'res' => 'error',
                'message' => 'Error al crear la sede: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, Sede $sede)
    {
        $request->validate([
            'institucion_id' => 'required|exists:instituciones,id',
            'nombre' => 'required|string|max:255',
            'codigo' => 'nullable|string|max:50',
            'direccion' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
        ]);

        try {
            $sede->update($request->all());
            
            Log::info('Sede actualizada', [
                'sede_id' => $sede->id,
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'res' => 'ok',
                'message' => 'Sede actualizada correctamente',
                'sede' => $sede->load('institucion')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'res' => 'error',
                'message' => 'Error al actualizar la sede: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show(Sede $sede)
    {
        return response()->json($sede);
    }

    public function destroy(Sede $sede)
    {
        try {
            $sede->delete();
            
            Log::info('Sede eliminada', [
                'sede_id' => $sede->id,
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'res' => 'ok',
                'message' => 'Sede eliminada correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'res' => 'error',
                'message' => 'Error al eliminar la sede: ' . $e->getMessage()
            ], 500);
        }
    }
}

