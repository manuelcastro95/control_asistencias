<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AlumnoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'codigo' => $this->codigo,
            'nombres' => $this->nombres,
            'apellidos' => $this->apellidos,
            'nombre_completo' => $this->full_name,
            'email' => $this->email,
            'telefono' => $this->telefono,
            'fecha_nacimiento' => $this->fecha_nacimiento?->format('Y-m-d'),
            'edad' => $this->edad,
            'genero' => $this->genero,
            'documento_identidad' => $this->documento_identidad,
            'direccion' => $this->direccion,
            'nombre_acudiente' => $this->nombre_acudiente,
            'telefono_acudiente' => $this->telefono_acudiente,
            'observaciones' => $this->observaciones,
            'activo' => $this->activo,
            'grado' => $this->whenLoaded('grado', function () {
                return [
                    'id' => $this->grado->id,
                    'nombre' => $this->grado->nombre,
                    'codigo' => $this->grado->codigo,
                    'sede' => $this->grado->sede ? [
                        'id' => $this->grado->sede->id,
                        'nombre' => $this->grado->sede->nombre,
                        'institucion' => $this->grado->sede->institucion ? [
                            'id' => $this->grado->sede->institucion->id,
                            'nombre' => $this->grado->sede->institucion->nombre,
                        ] : null,
                    ] : null,
                ];
            }),
            'total_asistencias' => $this->when(isset($this->asistencias_count), $this->asistencias_count),
            'qr_code' => $this->qr_base,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}

