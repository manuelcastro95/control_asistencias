<?php

namespace App\Services;

use App\Models\Asistencia;
use App\Models\Alumno;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AsistenciaService
{
    /**
     * Obtener asistencias con filtros (método legacy - mantener para compatibilidad)
     *
     * @param array $filters
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAsistencias(array $filters = [])
    {
        $query = Asistencia::with('alumno');

        // Filtro por fecha inicio
        if (isset($filters['fecha_inicio']) && $filters['fecha_inicio']) {
            $query->whereDate('fecha', '>=', $filters['fecha_inicio']);
        }

        // Filtro por fecha fin
        if (isset($filters['fecha_fin']) && $filters['fecha_fin']) {
            $query->whereDate('fecha', '<=', $filters['fecha_fin']);
        }

        // Filtro por alumno
        if (isset($filters['alumno_codigo']) && $filters['alumno_codigo']) {
            $query->whereHas('alumno', function ($q) use ($filters) {
                $q->where('codigo', $filters['alumno_codigo']);
            });
        }

        return $query->orderBy('fecha', 'desc')
            ->orderBy('hora', 'desc')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($a) {
                return [
                    'id' => $a->id,
                    'codigo' => $a->alumno->codigo,
                    'full_name' => $a->alumno->full_name,
                    'fecha' => $a->fecha,
                    'hora' => $a->hora,
                    'created_at' => $a->created_at,
                ];
            });
    }

    /**
     * Obtener asistencias paginadas para DataTables (optimizado)
     *
     * @param array $filters
     * @param int $start
     * @param int $length
     * @param string $search
     * @param string $orderBy
     * @param string $orderDir
     * @return array
     */
    public function getAsistenciasPaginated(array $filters = [], int $start = 0, int $length = 25, string $search = '', string $orderBy = 'fecha', string $orderDir = 'desc'): array
    {
        // Construir query base con join para mejor rendimiento
        $query = Asistencia::select([
                'asistencias.id',
                'asistencias.fecha',
                'asistencias.hora',
                'asistencias.created_at',
                'alumnos.codigo',
                'alumnos.nombres',
                'alumnos.apellidos'
            ])
            ->join('alumnos', 'asistencias.alumno_id', '=', 'alumnos.id')
            ->where('alumnos.activo', true);

        // Filtro por fecha inicio
        if (isset($filters['fecha_inicio']) && $filters['fecha_inicio']) {
            $query->whereDate('asistencias.fecha', '>=', $filters['fecha_inicio']);
        } else {
            // Por defecto, últimos 30 días si no hay filtro
            $query->whereDate('asistencias.fecha', '>=', Carbon::now()->subDays(30)->format('Y-m-d'));
        }

        // Filtro por fecha fin
        if (isset($filters['fecha_fin']) && $filters['fecha_fin']) {
            $query->whereDate('asistencias.fecha', '<=', $filters['fecha_fin']);
        }

        // Filtro por alumno
        if (isset($filters['alumno_codigo']) && $filters['alumno_codigo']) {
            $query->where('alumnos.codigo', $filters['alumno_codigo']);
        }

        // Búsqueda general
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('alumnos.codigo', 'like', "%{$search}%")
                  ->orWhere('alumnos.nombres', 'like', "%{$search}%")
                  ->orWhere('alumnos.apellidos', 'like', "%{$search}%");
            });
        }

        // Clonar query para contar (antes de ordenar y paginar)
        $countQuery = clone $query;
        $filtered = $countQuery->count();

        // Ordenar
        if ($orderBy === 'fecha') {
            $query->orderBy('asistencias.fecha', $orderDir)
                  ->orderBy('asistencias.hora', $orderDir);
        } elseif ($orderBy === 'codigo') {
            $query->orderBy('alumnos.codigo', $orderDir);
        } elseif ($orderBy === 'full_name') {
            $query->orderBy('alumnos.nombres', $orderDir)
                  ->orderBy('alumnos.apellidos', $orderDir);
        } else {
            $query->orderBy('asistencias.fecha', 'desc')
                  ->orderBy('asistencias.hora', 'desc');
        }

        // Paginar
        $asistencias = $query->skip($start)
            ->take($length)
            ->get();

        // Formatear datos
        $data = $asistencias->map(function ($a) {
            $fecha = Carbon::parse($a->fecha);
            $diasSemana = ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'];
            $diaSemana = $diasSemana[$fecha->dayOfWeek];
            $fechaFormateada = $fecha->locale('es')->isoFormat('D MMM YYYY');
            
            // Formatear hora
            $horaFormateada = '-';
            if ($a->hora) {
                try {
                    $hora = Carbon::createFromFormat('H:i:s', $a->hora);
                    $horaFormateada = $hora->format('H:i');
                } catch (\Exception $e) {
                    // Si el formato no es correcto, intentar parsearlo
                    $hora = Carbon::parse($a->hora);
                    $horaFormateada = $hora->format('H:i');
                }
            }

            return [
                '<span class="badge bg-primary">' . htmlspecialchars($a->codigo) . '</span>',
                htmlspecialchars($a->nombres . ' ' . $a->apellidos),
                '<div class="text-center"><div style="font-weight: 500;">' . $fechaFormateada . '</div><small class="text-muted" style="font-size: 0.75rem;"><i class="fas fa-clock me-1"></i>' . $horaFormateada . '</small></div>',
                '<span class="badge bg-info">' . $diaSemana . '</span>'
            ];
        });

        // Contar total (mismo query base pero sin búsqueda)
        $totalQuery = Asistencia::join('alumnos', 'asistencias.alumno_id', '=', 'alumnos.id')
            ->where('alumnos.activo', true);
        
        if (isset($filters['fecha_inicio']) && $filters['fecha_inicio']) {
            $totalQuery->whereDate('asistencias.fecha', '>=', $filters['fecha_inicio']);
        } else {
            $totalQuery->whereDate('asistencias.fecha', '>=', Carbon::now()->subDays(30)->format('Y-m-d'));
        }
        
        if (isset($filters['fecha_fin']) && $filters['fecha_fin']) {
            $totalQuery->whereDate('asistencias.fecha', '<=', $filters['fecha_fin']);
        }

        $total = $totalQuery->count();

        return [
            'data' => $data->values()->all(), // Asegurar que sea array indexado
            'total' => $total,
            'filtered' => $filtered
        ];
    }

    /**
     * Obtener estadísticas de asistencias
     *
     * @param string|null $fechaInicio
     * @param string|null $fechaFin
     * @return array
     */
    public function getEstadisticas(?string $fechaInicio = null, ?string $fechaFin = null): array
    {
        $query = Asistencia::query();

        if ($fechaInicio) {
            $query->whereDate('fecha', '>=', $fechaInicio);
        }

        if ($fechaFin) {
            $query->whereDate('fecha', '<=', $fechaFin);
        }

        $totalAsistencias = $query->count();
        $asistenciasHoy = Asistencia::whereDate('fecha', today())->count();
        $totalAlumnos = Alumno::count();

        // Asistencias por día (últimos 7 días)
        $asistenciasPorDia = Asistencia::whereDate('fecha', '>=', now()->subDays(7))
            ->selectRaw('DATE(fecha) as fecha, COUNT(*) as total')
            ->groupBy('fecha')
            ->orderBy('fecha')
            ->get();

        return [
            'total_asistencias' => $totalAsistencias,
            'asistencias_hoy' => $asistenciasHoy,
            'total_alumnos' => $totalAlumnos,
            'porcentaje_hoy' => $totalAlumnos > 0 ? round(($asistenciasHoy / $totalAlumnos) * 100, 2) : 0,
            'asistencias_por_dia' => $asistenciasPorDia,
        ];
    }

    /**
     * Eliminar una asistencia
     *
     * @param Asistencia $asistencia
     * @return bool
     */
    public function delete(Asistencia $asistencia): bool
    {
        try {
            $asistenciaId = $asistencia->id;
            $alumnoCodigo = $asistencia->alumno->codigo;
            $fecha = $asistencia->fecha;

            $asistencia->delete();

            Log::info('Asistencia eliminada', [
                'asistencia_id' => $asistenciaId,
                'alumno_codigo' => $alumnoCodigo,
                'fecha' => $fecha,
                'user_id' => auth()->id()
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Error al eliminar asistencia', [
                'asistencia_id' => $asistencia->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
}

