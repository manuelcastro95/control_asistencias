<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sede extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'institucion_id',
        'nombre',
        'codigo',
        'direccion',
        'telefono',
        'email',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function institucion()
    {
        return $this->belongsTo(Institucion::class);
    }

    public function grados()
    {
        return $this->hasMany(Grado::class);
    }

    public function gradosActivos()
    {
        return $this->hasMany(Grado::class)->where('activo', true);
    }
}

