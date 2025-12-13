<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Asistencia extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['alumno_id', 'fecha', 'hora'];

    protected $casts = [
        'fecha' => 'date',
        'hora' => 'string',
    ];

    public function alumno(){
        return $this->belongsTo(Alumno::class, 'alumno_id');
    }
}
