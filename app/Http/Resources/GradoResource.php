<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GradoResource extends JsonResource
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
            'nombre' => $this->nombre,
            'codigo' => $this->codigo,
            'orden' => $this->orden,
            'descripcion' => $this->descripcion,
            'activo' => $this->activo,
            'sede' => $this->whenLoaded('sede', function () {
                return [
                    'id' => $this->sede->id,
                    'nombre' => $this->sede->nombre,
                    'codigo' => $this->sede->codigo,
                    'institucion' => $this->sede->institucion ? [
                        'id' => $this->sede->institucion->id,
                        'nombre' => $this->sede->institucion->nombre,
                    ] : null,
                ];
            }),
            'total_alumnos' => $this->when(isset($this->alumnos_count), $this->alumnos_count),
        ];
    }
}

