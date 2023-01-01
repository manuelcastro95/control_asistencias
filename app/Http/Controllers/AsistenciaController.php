<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\Asistencia;
use Illuminate\Http\Request;

class AsistenciaController extends Controller
{
    
    public function index()
    {
        $asistencias = Asistencia::all()->map(function ($a) {
            return  [
                'codigo' => $a->alumno->codigo,
                'full_name' => $a->alumno->full_name,
                'fecha' => $a->fecha,
            ];
        });

        $alumnos = Alumno::all();
        return view('asistencias.index',compact('asistencias','alumnos'));
    }

    public function create()
    {
        //
    }

    
    public function store(Request $request)
    {
        //
    }

  
    public function show(Asistencia $asistencia)
    {
        //
    }

    
    public function edit(Asistencia $asistencia)
    {
        //
    }

   
    public function update(Request $request, Asistencia $asistencia)
    {
        //
    }

   
    public function destroy(Asistencia $asistencia)
    {
        //
    }
}
