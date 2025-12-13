<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Institucion extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'instituciones';

    protected $fillable = [
        'nombre',
        'nit',
        'direccion',
        'telefono',
        'email',
        'logo',
        'configuracion',
        'activo',
    ];

    protected $casts = [
        'configuracion' => 'array',
        'activo' => 'boolean',
    ];

    public function sedes()
    {
        return $this->hasMany(Sede::class);
    }

    public function sedesActivas()
    {
        return $this->hasMany(Sede::class)->where('activo', true);
    }
}

