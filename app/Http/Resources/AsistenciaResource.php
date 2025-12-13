<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AsistenciaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $fecha = \Carbon\Carbon::parse($this->fecha);
        
        // Formatear hora
        $horaFormateada = null;
        $horaCompleta = null;
        if ($this->hora) {
            try {
                // Intentar parsear como tiempo (H:i:s)
                $hora = \Carbon\Carbon::createFromFormat('H:i:s', $this->hora);
                $horaFormateada = $hora->format('H:i');
                $horaCompleta = $this->hora;
            } catch (\Exception $e) {
                // Si falla, usar el valor directamente
                $horaFormateada = substr($this->hora, 0, 5); // Tomar solo H:i
                $horaCompleta = $this->hora;
            }
        }
        
        return [
            'id' => $this->id,
            'fecha' => $this->fecha,
            'fecha_formateada' => $fecha->format('d/m/Y'),
            'hora' => $horaCompleta,
            'hora_formateada' => $horaFormateada,
            'fecha_hora_completa' => $horaFormateada ? $fecha->format('d/m/Y') . ' ' . $horaFormateada : $fecha->format('d/m/Y'),
            'dia_semana' => $fecha->locale('es')->dayName,
            'alumno' => $this->whenLoaded('alumno', function () {
                return [
                    'id' => $this->alumno->id,
                    'codigo' => $this->alumno->codigo,
                    'nombre_completo' => $this->alumno->full_name,
                    'grado' => $this->alumno->grado ? $this->alumno->grado->nombre : null,
                ];
            }),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}

