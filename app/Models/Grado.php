<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Grado extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'sede_id',
        'nombre',
        'codigo',
        'orden',
        'descripcion',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function sede()
    {
        return $this->belongsTo(Sede::class);
    }

    public function alumnos()
    {
        return $this->hasMany(Alumno::class);
    }

    public function alumnosActivos()
    {
        return $this->hasMany(Alumno::class)->where('activo', true);
    }

    public function getNombreCompletoAttribute()
    {
        return $this->nombre . ($this->sede ? ' - ' . $this->sede->nombre : '');
    }
}

