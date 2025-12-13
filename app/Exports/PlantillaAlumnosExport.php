<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class PlantillaAlumnosExport implements FromArray, WithHeadings, WithTitle
{
    /**
     * @return array
     */
    public function array(): array
    {
        return [];
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'codigo',
            'nombres',
            'apellidos',
            'grado',
            'email',
            'telefono',
            'fecha_nacimiento',
            'genero',
            'documento_identidad',
            'direccion',
            'nombre_acudiente',
            'telefono_acudiente',
            'observaciones'
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Alumnos';
    }
}

