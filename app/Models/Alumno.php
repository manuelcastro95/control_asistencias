<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class Alumno extends Model
{
    use HasFactory;
    protected $fillable = [
        'codigo',
        'nombres',
        'apellidos',
        'qr',
    ];

    public function asistencias(){
        return $this->hasMany(Asistencia::class);
    }

    public function getQrBaseAttribute(){
        return base64_encode(QrCode::format('png')->size(50)->generate($this->codigo));
    }

    public function getFullNameAttribute(){
        return $this->nombres.' '.$this->apellidos;
    }
}
