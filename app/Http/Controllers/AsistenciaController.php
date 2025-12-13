<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Services\AsistenciaService;
use Illuminate\Http\Request;

class AsistenciaController extends Controller
{
    protected $asistenciaService;

    public function __construct(AsistenciaService $asistenciaService)
    {
        $this->asistenciaService = $asistenciaService;
    }
    
    public function index(Request $request)
    {
        // Si es una petición AJAX de DataTables (verifica por parámetro draw)
        if ($request->has('draw') || $request->ajax()) {
            return $this->getAsistenciasAjax($request);
        }

        // Cargar solo los alumnos para el select (limitado)
        $alumnos = Alumno::where('activo', true)
            ->orderBy('nombres')
            ->limit(1000) // Limitar a 1000 para el select
            ->get();
        
        return view('asistencias.index', compact('alumnos'));
    }

    /**
     * Obtener asistencias vía AJAX para DataTables
     */
    public function getAsistenciasAjax(Request $request)
    {
        try {
            $filters = [
                'fecha_inicio' => $request->get('fecha_inicio'),
                'fecha_fin' => $request->get('fecha_fin'),
                'alumno_codigo' => $request->get('alumno_codigo'),
            ];

            // Parámetros de DataTables
            $start = intval($request->get('start', 0));
            $length = intval($request->get('length', 25));
            $search = $request->get('search')['value'] ?? '';
            
            // Ordenamiento
            $orderColumn = 2; // Por defecto fecha
            $orderDir = 'desc';
            
            if ($request->has('order') && isset($request->get('order')[0])) {
                $orderColumn = intval($request->get('order')[0]['column']);
                $orderDir = $request->get('order')[0]['dir'] ?? 'desc';
            }

            // Mapear columnas de DataTables a campos de BD
            $columns = ['codigo', 'full_name', 'fecha', 'dia'];
            $orderBy = isset($columns[$orderColumn]) ? $columns[$orderColumn] : 'fecha';

            // Obtener datos paginados
            $result = $this->asistenciaService->getAsistenciasPaginated(
                $filters, 
                $start, 
                $length, 
                $search, 
                $orderBy, 
                $orderDir
            );

            return response()->json([
                'draw' => intval($request->get('draw', 1)),
                'recordsTotal' => intval($result['total']),
                'recordsFiltered' => intval($result['filtered']),
                'data' => $result['data']
            ]);
        } catch (\Exception $e) {
            \Log::error('Error en getAsistenciasAjax: ' . $e->getMessage());
            return response()->json([
                'draw' => intval($request->get('draw', 1)),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => 'Error al cargar los datos'
            ], 500);
        }
    }

    public function create()
    {
        //
    }

    
    public function store(Request $request)
    {
        //
    }

  
    public function show(Asistencia $asistencia)
    {
        //
    }

    
    public function edit(Asistencia $asistencia)
    {
        //
    }

   
    public function update(Request $request, Asistencia $asistencia)
    {
        //
    }

   
    public function destroy(Asistencia $asistencia)
    {
        //
    }
}
