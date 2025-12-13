<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class Alumno extends Authenticatable
{
    use HasApiTokens, HasFactory, SoftDeletes, Notifiable;
    
    protected $fillable = [
        'grado_id',
        'codigo',
        'nombres',
        'apellidos',
        'email',
        'password',
        'telefono',
        'fecha_nacimiento',
        'genero',
        'documento_identidad',
        'direccion',
        'nombre_acudiente',
        'telefono_acudiente',
        'observaciones',
        'qr',
        'activo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
        'activo' => 'boolean',
        'email_verified_at' => 'datetime',
    ];

    public function grado()
    {
        return $this->belongsTo(Grado::class);
    }

    public function asistencias()
    {
        return $this->hasMany(Asistencia::class);
    }

    /**
     * Generar QR de alta calidad
     */
    public function getQrBaseAttribute()
    {
        // QR de alta calidad: tamaño 400, margen 2, corrección de errores 'H' (alta)
        return base64_encode(
            QrCode::format('png')
                ->size(400)
                ->margin(2)
                ->errorCorrection('H')
                ->generate($this->codigo)
        );
    }

    /**
     * Generar QR con información adicional (para impresión)
     */
    public function getQrCompletoAttribute()
    {
        $data = json_encode([
            'codigo' => $this->codigo,
            'nombre' => $this->full_name,
            'grado' => $this->grado ? $this->grado->nombre : null,
        ]);
        
        return base64_encode(
            QrCode::format('png')
                ->size(400)
                ->margin(2)
                ->errorCorrection('H')
                ->generate($data)
        );
    }

    public function getFullNameAttribute()
    {
        return $this->nombres . ' ' . $this->apellidos;
    }

    public function getEdadAttribute()
    {
        return $this->fecha_nacimiento ? $this->fecha_nacimiento->age : null;
    }

    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function scopePorGrado($query, $gradoId)
    {
        return $query->where('grado_id', $gradoId);
    }

    /**
     * Obtener el nombre del campo usado para autenticación
     */
    public function getAuthIdentifierName()
    {
        return 'codigo';
    }

    /**
     * Obtener el identificador único para autenticación
     */
    public function getAuthIdentifier()
    {
        return $this->codigo;
    }
}
