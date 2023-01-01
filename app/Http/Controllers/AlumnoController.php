<?php

namespace App\Http\Controllers;

use App\Helpers\ConfigHelper;
use App\Models\Alumno;
use App\Models\Asistencia;
use Carbon\Carbon;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class AlumnoController extends Controller
{
    
    public function index()
    {
        $alumnos = Alumno::all();
        return view('alumnos.index',compact('alumnos'));
    }

    public function store(Request $request)
    {
        $alumno = new Alumno();
        $alumno->codigo = $request->codigo;
        $alumno->nombres = $request->nombres;
        $alumno->apellidos = $request->apellidos;
        $alumno->save();
        $alumno->qr = $alumno->qr_base;
        $alumno->save();

        $alumnos = Alumno::all();

        return response()->json(['alumnos' => $alumnos, 'res' => 'ok']);

        // flash('Registro Guardado','success');
        // return back();
    }
    
    public function show(Alumno $alumno)
    {
        return $alumno;
    }

    public function save_record(Request $request){
        $alumno = Alumno::where('codigo',$request->codigo)->first();
        $hoy = Carbon::now()->format('Y-m-d');

        if(!is_null($alumno)):
            $validar = Asistencia::where('alumno_id',$alumno->id)->where('fecha', $hoy)->get();

            if($validar->count() > 0):
                $res = ['msg' => 'Ya registro asistencia', 'level' => 'warning'];
            else:
                $asistencia =  new Asistencia();
                $asistencia->alumno_id = $alumno->id;
                $asistencia->fecha = $hoy;
                $asistencia->save();

                $res = ['msg' => 'Asistencia registrada', 'level' => 'success'];;
            endif;
        else:
            $res = ['msg' => 'No se encontro el alumno', 'level' => 'error'];;
        endif;

        return response()->json($res);
    }

   
    public function edit(Alumno $alumno)
    {
        //
    }

  
    public function update(Request $request, Alumno $alumno)
    {
        $alumno->codigo = $request->codigo;
        $alumno->nombres = $request->nombres;
        $alumno->apellidos = $request->apellidos;
        $alumno->save();
        $alumno->qr = $alumno->qr_base;
        $alumno->save();

        $alumnos = Alumno::all();
        return response()->json(['alumnos' => $alumnos, 'res' => 'ok']);
    }

    public function destroy(Alumno $alumno)
    {
       $alumno->delete();

       $alumnos = Alumno::all();
       return response()->json(['alumnos' => $alumnos, 'res' => 'ok']);
    }
}
