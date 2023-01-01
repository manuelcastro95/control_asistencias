@extends('layouts.app')
@section('titulo')
    Alumnos
@endsection
@section('menu_select')
    {{$select = 'alumnos'}}
@endsection
@section('content')
   
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">

                    <button type="button" class="btn btn-primary btn-sm"
                        onclick="alumno('agregar','-','{{ route('alumnos.store') }}')" title="registrar alumno">
                        <i class="fas fa-user-plus ml-2"></i> Registrar Alumno
                    </button>

                    <div class="table-responsive my-4">
                        <table id="tableAlumnos" class="table table-striped display" style="width:100%">
                            <thead class="thead-light">
                                <tr>
                                    <th class="text-center">Codigo</th>
                                    <th class="text-center">Nombres</th>
                                    <th class="text-center">Apellidos</th>
                                    <th class="text-center">Acciones</th>
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
    {{-- Modal agregar Alumno --}}
    <div class="modal fade" id="modalAddAlumno" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog        ">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAlumnoTitle"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" id="form_save">
                    @csrf
                    <input type="hidden" name="_method" id="_method">
                    <input type="hidden" id="input_accion">

                    <div class="modal-body">
                        <div class="row">
                            <div class="input-group my-2">
                                <label for="nombre" class="input-group-text col-sm-3">Codigo</label>
                                <input type="codigo" class="form-control col-sm-9" name="codigo" id="codigo">
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-group my-2">
                                <label for="nombre" class="input-group-text col-sm-3">Nombres</label>
                                <input type="text" class="form-control col-sm-9" name="nombres" id="nombres">
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-group my-2">
                                <label for="apellidos" class="input-group-text col-sm-3">Apellidos</label>
                                <input type="text" class="form-control col-sm-9" name="apellidos" id="apellidos">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        {{-- <button type="submit" class="btn btn-primary">Guardar</button> --}}
                        <button type="button" class="btn btn-primary" onclick="guardar()">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal show Alumno --}}
    <div class="modal fade" id="modalShowAlumno" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog        ">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ver alumno</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="input-group my-2 col-md-12">
                            <label for="nombre" class="input-group-text col-sm-3">Codigo</label>
                            <input type="codigo" class="form-control col-sm-9" id="s_codigo" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-group my-2 col-md-12">
                            <label for="nombre" class="input-group-text col-sm-3">Nombres</label>
                            <input type="text" class="form-control col-sm-9" id="s_nombres" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-group my-2 col-md-12">
                            <label for="apellidos" class="input-group-text col-sm-3">Apellidos</label>
                            <input type="text" class="form-control col-sm-9" id="s_apellidos" readonly>
                        </div>
                    </div>
                    <div class="row justify-content-md-center">
                        <div class="col-md-12 my-2 text-center">
                            <img id="qr_alumno" class="img-fluid" alt="..." width="180">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        let codigo = document.getElementById('codigo');
        let nombres = document.getElementById('nombres');
        let apellidos = document.getElementById('apellidos');
        let input_accion = document.getElementById('input_accion');

        let alumnos = Object.values(@json($alumnos));
        let table = $('#tableAlumnos').DataTable({
            "language": {!! ConfigHelper::languageDataTable() !!},
        });

        $(document).ready(function() {
            loadTable(alumnos);
        })

        const alumno = (accion, url_show, url) => {
            input_accion.value = accion;
            if (accion == 'agregar') {
                codigo.value = '';
                nombres.value = '';
                apellidos.value = '';
                $('#modalAlumnoTitle').html('Registrar Alumno');
                $('#_method').val('POST');
            } else if (accion == 'editar') {
                show_alumno('edit', url_show);
                $('#modalAlumnoTitle').html('Editar alumno');
                $('#_method').val('PUT');
            } else {
                show_alumno('show', url_show);
            }
            $('#form_save').attr('action', url);
            $('#modalAddAlumno').modal('show');
        }

        const save_alumno = () => {
            let data = {
                codigo: codigo.value,
                nombres: nombres.value,
                apellidos: apellidos.value,
            };
            let url = $('#form_save').attr('action');
            fetch(url, {
                method: 'POST', // or 'PUT'
                body: JSON.stringify(data), // data can be `string` or {object}!
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }).then(res => res.json())
            .catch(error => console.error('Error:', error))
            .then(response => {
                if (response.res == 'ok') {

                    $('#modalAddAlumno').modal('hide');
                    codigo.value = '';
                    nombres.value = '';
                    apellidos.value = '';


                    Swal.fire({
                        title: 'Registro Guardado',
                        text: '',
                        icon: 'success',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'success',
                        confirmButtonText: 'cerrar'
                    })

                    loadTable(response.alumnos);
                }
            });
        }

        const update_alumno = () => {
            let data = {
                codigo: codigo.value,
                nombres: nombres.value,
                apellidos: apellidos.value,
            };
            let url = $('#form_save').attr('action');

            fetch(url, {
                    method: 'PUT',
                    body: JSON.stringify(data),
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                }).then(res => res.json())
                .catch(error => console.error('Error:', error))
                .then(response => {
                    if (response.res == 'ok') {

                        $('#modalAddAlumno').modal('hide');
                        codigo.value = '';
                        nombres.value = '';
                        apellidos.value = '';

                        Swal.fire({
                            title: 'Registro Actualizado',
                            text: '',
                            icon: 'success',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'success',
                            confirmButtonText: 'cerrar'
                        })

                        loadTable(response.alumnos);
                    }
                });
        }

        const guardar = () => {
            if (codigo.value == '' || nombres.value == '' || apellidos.value == '') {
                Swal.fire({
                    title: 'Verifique los Campos en blanco',
                    text: '',
                    icon: 'warning',
                    showCancelButton: false,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'success',
                    confirmButtonText: 'cerrar'
                })
            } else {
                console.log(input_accion);
                if (input_accion.value == 'agregar') {
                    save_alumno();
                } else if (input_accion.value == 'editar') {
                    update_alumno();
                }
            }
        }

        const show_alumno = async (act, url) => {
            let alumno = await fetch(url)
                .then(response => response.json())
                .then(res => res)

            if (act == 'show') {
                $('#s_codigo').val(alumno.codigo);
                $('#s_nombres').val(alumno.nombres);
                $('#s_apellidos').val(alumno.apellidos);
                $('#qr_alumno').attr('src', `data:image/png;base64,${alumno.qr}`);
                $('#modalShowAlumno').modal('show');
            } else {
                codigo.value = alumno.codigo;
                nombres.value = alumno.nombres;
                apellidos.value = alumno.apellidos;
            }
        }

        const delete_alumno = url_delete => {
            console.log(url_delete);
            Swal.fire({
                title: 'Estas seguro de eliminar este alumno?',
                text: "Esta acción no se podrá revertir",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, eliminar!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(url_delete, {
                        method: 'DELETE', 
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    }).then(res => res.json())
                    .catch(error => console.error('Error:', error))
                    .then(response => {
                        if (response.res == 'ok') {
                            Swal.fire(
                                'Eliminado!',
                                'El Alumno fue eliminado.',
                                'success'
                            )
                            loadTable(response.alumnos);
                        }
                    });
                }
            });
        }

        const loadTable = alumnos => {
            resetTable();
            alumnos.forEach(alumno => {
                let ruta_show = `/alumnos/${alumno.id}/show`;
                let ruta_update = `/alumnos/${alumno.id}`;
                let ruta_destroy = `/alumnos/${alumno.id}/destroy`;

                table.row.add([
                    alumno.codigo,
                    alumno.nombres,
                    alumno.apellidos,
                    `   
                    <div class="btn-group">
                        <button type="button" onclick="show_alumno('show','${ruta_show}')" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i></button>
                        <button type="button" class="btn btn-secondary btn-sm"  onclick="alumno('editar','${ruta_show}','${ruta_update}')"><i class="fas fa-user-edit"></i></button>
                        <button type="button" class="btn btn-danger btn-sm" onclick="delete_alumno('${ruta_destroy}')"><i class="fas fa-trash-alt"></i></button>
                    </div>
                    `,
                ]).draw(false);
            });
        }

        // limpiar tabla
        const resetTable = () => {
            table.clear().draw();
        }
    </script>
@endsection
