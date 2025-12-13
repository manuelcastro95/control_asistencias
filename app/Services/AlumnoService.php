<?php

namespace App\Services;

use App\Models\Alumno;
use App\Models\Asistencia;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AlumnoService
{
    /**
     * Crear un nuevo alumno
     *
     * @param array $data
     * @return Alumno
     */
    public function create(array $data): Alumno
    {
        try {
            DB::beginTransaction();

            $alumno = new Alumno();
            $alumno->codigo = $data['codigo'];
            $alumno->nombres = $data['nombres'];
            $alumno->apellidos = $data['apellidos'];
            $alumno->grado_id = $data['grado_id'] ?? null;
            $alumno->email = $data['email'] ?? null;
            $alumno->telefono = $data['telefono'] ?? null;
            $alumno->fecha_nacimiento = $data['fecha_nacimiento'] ?? null;
            $alumno->genero = $data['genero'] ?? null;
            $alumno->documento_identidad = $data['documento_identidad'] ?? null;
            $alumno->direccion = $data['direccion'] ?? null;
            $alumno->nombre_acudiente = $data['nombre_acudiente'] ?? null;
            $alumno->telefono_acudiente = $data['telefono_acudiente'] ?? null;
            $alumno->observaciones = $data['observaciones'] ?? null;
            $alumno->activo = $data['activo'] ?? true;
            $alumno->save();

            // Generar QR después de guardar
            $alumno->qr = $alumno->qr_base;
            $alumno->save();

            DB::commit();

            Log::info('Alumno creado', [
                'alumno_id' => $alumno->id,
                'codigo' => $alumno->codigo,
                'user_id' => auth()->id()
            ]);

            return $alumno;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al crear alumno', [
                'error' => $e->getMessage(),
                'data' => $data
            ]);
            throw $e;
        }
    }

    /**
     * Actualizar un alumno
     *
     * @param Alumno $alumno
     * @param array $data
     * @return Alumno
     */
    public function update(Alumno $alumno, array $data): Alumno
    {
        try {
            DB::beginTransaction();

            $alumno->codigo = $data['codigo'];
            $alumno->nombres = $data['nombres'];
            $alumno->apellidos = $data['apellidos'];
            $alumno->grado_id = $data['grado_id'] ?? $alumno->grado_id;
            $alumno->email = $data['email'] ?? $alumno->email;
            $alumno->telefono = $data['telefono'] ?? $alumno->telefono;
            $alumno->fecha_nacimiento = $data['fecha_nacimiento'] ?? $alumno->fecha_nacimiento;
            $alumno->genero = $data['genero'] ?? $alumno->genero;
            $alumno->documento_identidad = $data['documento_identidad'] ?? $alumno->documento_identidad;
            $alumno->direccion = $data['direccion'] ?? $alumno->direccion;
            $alumno->nombre_acudiente = $data['nombre_acudiente'] ?? $alumno->nombre_acudiente;
            $alumno->telefono_acudiente = $data['telefono_acudiente'] ?? $alumno->telefono_acudiente;
            $alumno->observaciones = $data['observaciones'] ?? $alumno->observaciones;
            if (isset($data['activo'])) {
                $alumno->activo = $data['activo'];
            }
            $alumno->save();

            // Regenerar QR si cambió el código
            if ($alumno->wasChanged('codigo')) {
                $alumno->qr = $alumno->qr_base;
                $alumno->save();
            }

            DB::commit();

            Log::info('Alumno actualizado', [
                'alumno_id' => $alumno->id,
                'codigo' => $alumno->codigo,
                'user_id' => auth()->id()
            ]);

            return $alumno;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al actualizar alumno', [
                'alumno_id' => $alumno->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Eliminar un alumno
     *
     * @param Alumno $alumno
     * @return bool
     */
    public function delete(Alumno $alumno): bool
    {
        try {
            DB::beginTransaction();

            $alumnoId = $alumno->id;
            $codigo = $alumno->codigo;

            // Eliminar asistencias relacionadas
            Asistencia::where('alumno_id', $alumnoId)->delete();

            $alumno->delete();

            DB::commit();

            Log::info('Alumno eliminado', [
                'alumno_id' => $alumnoId,
                'codigo' => $codigo,
                'user_id' => auth()->id()
            ]);

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al eliminar alumno', [
                'alumno_id' => $alumno->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Registrar asistencia de un alumno
     *
     * @param string $codigo
     * @return array
     */
    public function registrarAsistencia(string $codigo): array
    {
        try {
            $alumno = Alumno::where('codigo', $codigo)->first();

            if (!$alumno) {
                return [
                    'success' => false,
                    'msg' => 'No se encontró el alumno',
                    'level' => 'error'
                ];
            }

            $hoy = Carbon::now()->format('Y-m-d');
            $hora = Carbon::now()->format('H:i:s');

            // Verificar si ya registró asistencia hoy
            $asistenciaExistente = Asistencia::where('alumno_id', $alumno->id)
                ->whereDate('fecha', $hoy)
                ->first();

            if ($asistenciaExistente) {
                return [
                    'success' => false,
                    'msg' => 'Ya registró asistencia hoy',
                    'level' => 'warning',
                    'asistencia' => $asistenciaExistente
                ];
            }

            // Crear nueva asistencia
            $asistencia = new Asistencia();
            $asistencia->alumno_id = $alumno->id;
            $asistencia->fecha = $hoy;
            $asistencia->hora = $hora;
            $asistencia->save();

            Log::info('Asistencia registrada', [
                'alumno_id' => $alumno->id,
                'codigo' => $codigo,
                'fecha' => $hoy,
                'hora' => $hora,
                'user_id' => auth()->id()
            ]);

            return [
                'success' => true,
                'msg' => 'Asistencia registrada correctamente',
                'level' => 'success',
                'alumno' => $alumno->full_name,
                'asistencia' => $asistencia
            ];
        } catch (\Exception $e) {
            Log::error('Error al registrar asistencia', [
                'codigo' => $codigo,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'msg' => 'Error al registrar la asistencia',
                'level' => 'error'
            ];
        }
    }
}

