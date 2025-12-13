<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InstitucionResource extends JsonResource
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
            'nit' => $this->nit,
            'direccion' => $this->direccion,
            'telefono' => $this->telefono,
            'email' => $this->email,
            'logo' => $this->logo,
            'activo' => $this->activo,
            'sedes' => SedeResource::collection($this->whenLoaded('sedes')),
            'total_sedes' => $this->when(isset($this->sedes_count), $this->sedes_count),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}

