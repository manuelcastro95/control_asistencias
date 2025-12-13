@extends('layouts.app')

@section('titulo')
    Asistencias
@endsection

@section('menu_select')
    {{$select = 'asistencias'}}
@endsection

@section('content')
<div class="container-fluid py-3 px-3">
    <div class="d-flex justify-content-between align-items-center mb-3"></div>
        <div>
            <h4 class="mb-1 text-gray-800 fw-bold">
                <i class="fas fa-clipboard-check me-2 text-primary"></i>Registro de Asistencias
            </h4>
            <small class="text-muted">Consulta y filtra el registro de asistencias</small>
        </div>
    </div>

    <div class="card shadow-sm mb-3">
        <div class="card-header py-2 px-3 bg-light">
            <h6 class="m-0 fw-semibold text-primary" style="font-size: 0.875rem;">
                <i class="fas fa-filter me-1"></i>Filtros
            </h6>
        </div>
        <div class="card-body p-3">
            <form class="row g-2">
                <div class="col-md-3 col-sm-6">
                    <label for="f_inicio" class="form-label small fw-semibold mb-1">
                        <i class="fas fa-calendar-alt me-1 text-primary"></i>Fecha Inicio
                    </label>
                    <input type="date" class="form-control form-control-sm" id="f_inicio">
                </div>
                <div class="col-md-3 col-sm-6">
                    <label for="f_fin" class="form-label small fw-semibold mb-1">
                        <i class="fas fa-calendar-alt me-1 text-primary"></i>Fecha Fin
                    </label>
                    <input type="date" class="form-control form-control-sm" id="f_fin">
                </div>
                <div class="col-md-4 col-sm-6">
                    <label for="alumno" class="form-label small fw-semibold mb-1">
                        <i class="fas fa-user me-1 text-primary"></i>Alumno
                    </label>
                    <select class="select2 form-select form-select-sm" style="width: 100%" id="alumno">
                        <option value="">Todos los alumnos</option>
                        @foreach ($alumnos as $alumno)
                            <option value="{{$alumno->codigo}}">{{$alumno->codigo}} - {{$alumno->full_name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 col-sm-6 d-flex align-items-end">
                    <button type="button" class="btn btn-primary btn-sm w-100 me-1" title="buscar" onclick="buscar()">
                        <i class="fas fa-search"></i>
                    </button>
                    <button type="button" class="btn btn-secondary btn-sm" title="limpiar" onclick="reset_filter()">
                        <i class="fas fa-eraser"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header py-2 px-3 bg-light">
            <h6 class="m-0 fw-semibold text-primary" style="font-size: 0.875rem;">
                <i class="fas fa-table me-1"></i>Lista de Asistencias
            </h6>
        </div>
        <div class="card-body p-3">
            <div class="table-responsive">
                <table id="tableAsistencias" class="table table-sm table-hover table-striped display" style="width:100%">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 110px;">
                                <i class="fas fa-barcode me-1"></i>Código
                            </th>
                            <th>
                                <i class="fas fa-user me-1"></i>Alumno
                            </th>
                            <th class="text-center" style="width: 160px;">
                                <i class="fas fa-calendar me-1"></i>Fecha y Hora
                            </th>
                            <th class="text-center" style="width: 100px;">
                                <i class="fas fa-calendar-day me-1"></i>Día
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let f_inicio = document.getElementById('f_inicio');
    let f_fin = document.getElementById('f_fin');
    let alumno = document.getElementById('alumno');

    // Establecer fecha por defecto (últimos 30 días)
    const fechaFin = new Date();
    const fechaInicio = new Date();
    fechaInicio.setDate(fechaInicio.getDate() - 30);
    
    f_fin.value = fechaFin.toISOString().split('T')[0];
    f_inicio.value = fechaInicio.toISOString().split('T')[0];

    // DataTable con server-side processing
    let table = $('#tableAsistencias').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "{{ route('asistencias.index') }}",
            "type": "GET",
            "data": function(d) {
                d.fecha_inicio = f_inicio.value || '';
                d.fecha_fin = f_fin.value || '';
                d.alumno_codigo = alumno.value || '';
            },
            "error": function(xhr, error, thrown) {
                console.error('Error en DataTables:', error);
                Swal.fire({
                    title: 'Error',
                    text: 'Error al cargar los datos. Por favor, recarga la página.',
                    icon: 'error'
                });
            }
        },
        "language": {!! ConfigHelper::languageDataTable() !!},
        "pageLength": 15,
        "lengthMenu": [[10, 15, 25, 50], [10, 15, 25, 50]],
        "order": [[2, 'desc']],
        "columns": [
            { "data": 0, "orderable": true, "searchable": true },
            { "data": 1, "orderable": true, "searchable": true },
            { "data": 2, "orderable": true, "searchable": false },
            { "data": 3, "orderable": false, "searchable": false }
        ],
        "dom": '<"row"<"col-sm-12 col-md-6"B><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
        "buttons": [
            {
                extend: "excelHtml5",
                text: '<i class="fas fa-file-excel me-1"></i>Excel',
                title: 'Reporte de Asistencias',
                titleAttr: 'Exportar a Excel',
                className: 'btn btn-success',
                exportOptions: {
                    columns: [0, 1, 2]
                },
                action: function(e, dt, button, config) {
                    let params = new URLSearchParams({
                        fecha_inicio: f_inicio.value || '',
                        fecha_fin: f_fin.value || '',
                        alumno_codigo: alumno.value || '',
                        export: 'excel'
                    });
                    window.location.href = "{{ route('asistencias.index') }}?" + params.toString();
                }
            },
            {
                extend: "pdfHtml5",
                text: '<i class="fas fa-file-pdf me-1"></i>PDF',
                title: 'Reporte de Asistencias',
                titleAttr: 'Exportar a PDF',
                className: 'btn btn-danger',
                exportOptions: {
                    columns: [0, 1, 2]
                },
                action: function(e, dt, button, config) {
                    let params = new URLSearchParams({
                        fecha_inicio: f_inicio.value || '',
                        fecha_fin: f_fin.value || '',
                        alumno_codigo: alumno.value || '',
                        export: 'pdf'
                    });
                    window.location.href = "{{ route('asistencias.index') }}?" + params.toString();
                }
            }
        ],
        "drawCallback": function(settings) {
            // La tabla se actualiza automáticamente
        }
    });

    // Función para buscar (recargar tabla con filtros)
    const buscar = () => {
        table.ajax.reload();
    }

    // Función para resetear filtros
    const reset_filter = () => {
        f_inicio.value = '';
        f_fin.value = '';
        $("#alumno").val(null).trigger("change");
        table.ajax.reload();
    }

    // Recargar tabla cuando cambien los filtros
    $('#f_inicio, #f_fin').on('change', function() {
        table.ajax.reload();
    });

    $('#alumno').on('change', function() {
        table.ajax.reload();
    });
</script>
@endsection
