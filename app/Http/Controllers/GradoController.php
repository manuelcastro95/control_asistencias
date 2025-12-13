<?php

namespace App\Http\Controllers;

use App\Models\Grado;
use App\Models\Sede;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GradoController extends Controller
{
    public function index(Request $request)
    {
        $query = Grado::with('sede.institucion');
        
        if ($request->has('sede_id')) {
            $query->where('sede_id', $request->sede_id);
        }
        
        $grados = $query->orderBy('orden')->get();
        $sedes = Sede::where('activo', true)->with('institucion')->get();
        
        return view('configuracion.grados.index', compact('grados', 'sedes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sede_id' => 'required|exists:sedes,id',
            'nombre' => 'required|string|max:255',
            'codigo' => 'nullable|string|max:50',
            'orden' => 'nullable|integer|min:0',
            'descripcion' => 'nullable|string',
        ]);

        try {
            $grado = Grado::create($request->all());
            
            Log::info('Grado creado', [
                'grado_id' => $grado->id,
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'res' => 'ok',
                'message' => 'Grado creado correctamente',
                'grado' => $grado->load('sede.institucion')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'res' => 'error',
                'message' => 'Error al crear el grado: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, Grado $grado)
    {
        $request->validate([
            'sede_id' => 'required|exists:sedes,id',
            'nombre' => 'required|string|max:255',
            'codigo' => 'nullable|string|max:50',
            'orden' => 'nullable|integer|min:0',
            'descripcion' => 'nullable|string',
        ]);

        try {
            $grado->update($request->all());
            
            Log::info('Grado actualizado', [
                'grado_id' => $grado->id,
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'res' => 'ok',
                'message' => 'Grado actualizado correctamente',
                'grado' => $grado->load('sede.institucion')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'res' => 'error',
                'message' => 'Error al actualizar el grado: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show(Grado $grado)
    {
        return response()->json($grado);
    }

    public function destroy(Grado $grado)
    {
        try {
            $grado->delete();
            
            Log::info('Grado eliminado', [
                'grado_id' => $grado->id,
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'res' => 'ok',
                'message' => 'Grado eliminado correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'res' => 'error',
                'message' => 'Error al eliminar el grado: ' . $e->getMessage()
            ], 500);
        }
    }
}

