<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SedeResource extends JsonResource
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
            'direccion' => $this->direccion,
            'telefono' => $this->telefono,
            'email' => $this->email,
            'activo' => $this->activo,
            'institucion' => $this->whenLoaded('institucion', function () {
                return [
                    'id' => $this->institucion->id,
                    'nombre' => $this->institucion->nombre,
                ];
            }),
            'grados' => GradoResource::collection($this->whenLoaded('grados')),
            'total_grados' => $this->when(isset($this->grados_count), $this->grados_count),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}

