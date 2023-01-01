@extends('layouts.app')

@section('titulo')
    Asistencias
@endsection

@section('menu_select')
    {{$select = 'asistencias'}}
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card ">
            <div class="card">
                <div class="card-body">

                    <form class="form-inline">
                        <div class="row">
                            <div class="my-2 col-md-3">
                                <label for="formGroupExampleInput" class="form-label">Fecha inicio</label>
                                <input type="date" class="form-control" id="f_inicio">
                            </div>
                            <div class="my-2 col-md-3">
                                <label for="formGroupExampleInput" class="form-label">Fecha fin</label>
                                <input type="date" class="form-control" id="f_fin">
                            </div>

                            <div class="my-2 col-md-3">
                                <label for="formGroupExampleInput" class="form-label">Alumno</label>
                                <select class="select2 form-control" style="width: 100%" id="alumno">
                                    <option></option>
                                    @foreach ($alumnos as $alumno)
                                        <option value="{{$alumno->codigo}}"><span class="hidden">{{$alumno->codigo}}</span> - {{$alumno->full_name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="my-2 col-md-3">
                                <button type="button" class="btn btn-primary mt-4" title="buscar" onclick="buscar()"><i class="fas fa-search"></i></button>
                                <button type="button" class="btn btn-secondary mt-4" title="limpiar busqueda" onclick="reset_filter()"><i class="fas fa-eraser"></i></button>
                            </div>
                            
                        </div>
                    </form>

                    <div class="table-responsive my-4">
                        <table id="tableAsistencias" class="table table-striped display" style="width:100%">
                            <thead class="thead-light">
                                <tr>
                                    <th class="text-center">Codigo</th>
                                    <th class="text-center">Nombres y Apellidos</th>
                                    <th class="text-center">Fecha</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
@endsection

@section('scripts')
    <script>
        let table = $('#tableAsistencias').DataTable({
            "language": {!! ConfigHelper::languageDataTable() !!},
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: "excelHtml5",
                    text: '<i class="mdi mdi-file-excel"></i>Excel', 
                    title: 'Reporte de asistencias',
                    titleAttr: 'Exportar a Excel', 
                    className: 'btn btn-app export excel'
                }
            ]
        });
        let asistencias = Object.values(@json($asistencias));
        let f_inicio = document.getElementById('f_inicio');
        let f_fin = document.getElementById('f_fin');
        let alumno = document.getElementById('alumno');

        $(document).ready(function () {
            console.log(asistencias);
            loadTable(asistencias);
        })
        
        const loadTable = asistencias => {
            resetTable();
            asistencias.forEach(asistencia => {

                table.row.add([
                    asistencia.codigo,
                    asistencia.full_name,
                    asistencia.fecha,
                ]).draw(false);
            });
        }

        // limpiar tabla
        const resetTable = () => {
            table.clear().draw();
        }


        //resetear filtros
        const reset_filter = () =>{
            f_inicio.value = '';
            f_fin.value = '';
            $("#alumno").val(null).trigger("change");
            resetTable();
            loadTable(asistencias);
        }


        const buscar = () => {
            let filter = asistencias;

            if(f_inicio.value != '' && f_fin.value != '' ){
                filter = filter.filter(asistencia => asistencia.fecha >= f_inicio.value && asistencia.fecha <= f_fin.value )
            }
            if(alumno.value != ''){
                filter = filter.filter(asistencia => asistencia.codigo == alumno.value  )
            }


            loadTable(filter);
            console.log(alumno.value);
        }
    </script> 
@endsection
