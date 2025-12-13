<?php

namespace App\Http\Controllers;

use App\Helpers\ConfigHelper;
use App\Http\Requests\StoreAlumnoRequest;
use App\Http\Requests\UpdateAlumnoRequest;
use App\Http\Requests\SaveAsistenciaRequest;
use App\Exports\PlantillaAlumnosExport;
use App\Imports\AlumnosImport;
use App\Models\Alumno;
use App\Models\Grado;
use App\Services\AlumnoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class AlumnoController extends Controller
{
    protected $alumnoService;

    public function __construct(AlumnoService $alumnoService)
    {
        $this->alumnoService = $alumnoService;
    }
    
    public function index(Request $request)
    {
        $query = Alumno::with('grado.sede.institucion');
        
        // Filtros
        if ($request->has('grado_id') && $request->grado_id) {
            $query->where('grado_id', $request->grado_id);
        }
        
        if ($request->has('activo') && $request->activo !== '') {
            $query->where('activo', $request->activo);
        }
        
        if ($request->has('buscar') && $request->buscar) {
            $buscar = $request->buscar;
            $query->where(function($q) use ($buscar) {
                $q->where('codigo', 'like', "%{$buscar}%")
                  ->orWhere('nombres', 'like', "%{$buscar}%")
                  ->orWhere('apellidos', 'like', "%{$buscar}%")
                  ->orWhere('documento_identidad', 'like', "%{$buscar}%");
            });
        }
        
        $alumnos = $query->orderBy('created_at', 'desc')->get();
        $grados = Grado::with('sede.institucion')->where('activo', true)->orderBy('orden')->get();
        
        return view('alumnos.index', compact('alumnos', 'grados'));
    }

    /**
     * Importar alumnos desde Excel
     */
    public function importar(Request $request): JsonResponse
    {
        $request->validate([
            'archivo' => 'required|mimes:xlsx,xls,csv|max:10240'
        ]);

        try {
            $import = new AlumnosImport($this->alumnoService);
            Excel::import($import, $request->file('archivo'));
            
            $resultados = $import->getResults();
            
            return response()->json([
                'res' => 'ok',
                'message' => "Importación completada: {$resultados['importados']} importados, {$resultados['fallidos']} fallidos",
                'importados' => $resultados['importados'],
                'fallidos' => $resultados['fallidos'],
                'errors' => $resultados['errors']
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'res' => 'error',
                'message' => 'Error al importar: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Descargar plantilla Excel
     */
    public function descargarPlantilla()
    {
        return Excel::download(
            new PlantillaAlumnosExport(),
            'plantilla_importacion_alumnos.xlsx'
        );
    }

    public function store(StoreAlumnoRequest $request): JsonResponse
    {
        try {
            $alumno = $this->alumnoService->create($request->validated());
            $alumnos = Alumno::orderBy('created_at', 'desc')->get();

            return response()->json([
                'alumnos' => $alumnos,
                'res' => 'ok',
                'message' => 'Alumno registrado correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'res' => 'error',
                'message' => 'Error al registrar el alumno: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function show(Alumno $alumno): JsonResponse
    {
        $alumno->load('grado.sede.institucion');
        
        return response()->json([
            'id' => $alumno->id,
            'codigo' => $alumno->codigo,
            'nombres' => $alumno->nombres,
            'apellidos' => $alumno->apellidos,
            'grado_id' => $alumno->grado_id,
            'grado' => $alumno->grado ? $alumno->grado->nombre : null,
            'email' => $alumno->email,
            'telefono' => $alumno->telefono,
            'fecha_nacimiento' => $alumno->fecha_nacimiento,
            'genero' => $alumno->genero,
            'documento_identidad' => $alumno->documento_identidad,
            'direccion' => $alumno->direccion,
            'nombre_acudiente' => $alumno->nombre_acudiente,
            'telefono_acudiente' => $alumno->telefono_acudiente,
            'observaciones' => $alumno->observaciones,
            'qr' => $alumno->qr_base
        ]);
    }

    public function save_record(SaveAsistenciaRequest $request): JsonResponse
    {
        $result = $this->alumnoService->registrarAsistencia($request->validated()['codigo']);
        
        return response()->json([
            'msg' => $result['msg'],
            'level' => $result['level'],
            'success' => $result['success']
        ]);
    }

    public function update(UpdateAlumnoRequest $request, Alumno $alumno): JsonResponse
    {
        try {
            $this->alumnoService->update($alumno, $request->validated());
            $alumnos = Alumno::orderBy('created_at', 'desc')->get();

            return response()->json([
                'alumnos' => $alumnos,
                'res' => 'ok',
                'message' => 'Alumno actualizado correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'res' => 'error',
                'message' => 'Error al actualizar el alumno: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Alumno $alumno): JsonResponse
    {
        try {
            $this->alumnoService->delete($alumno);
            $alumnos = Alumno::orderBy('created_at', 'desc')->get();

            return response()->json([
                'alumnos' => $alumnos,
                'res' => 'ok',
                'message' => 'Alumno eliminado correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'res' => 'error',
                'message' => 'Error al eliminar el alumno: ' . $e->getMessage()
            ], 500);
        }
    }
}
