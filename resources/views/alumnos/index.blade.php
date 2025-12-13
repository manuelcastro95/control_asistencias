@extends('layouts.app')
@section('titulo')
    Alumnos
@endsection
@section('menu_select')
    {{$select = 'alumnos'}}
@endsection
@section('content')
<div class="container-fluid py-5 px-4">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h2 class="h3 mb-0 text-gray-800">
                <i class="fas fa-users me-2 text-primary"></i>Gestión de Alumnos
            </h2>
            <p class="text-muted mb-0">Administra el registro de alumnos del sistema</p>
        </div>
        <div class="btn-group">
            <button type="button" class="btn btn-success btn-lg shadow-sm" onclick="abrirImportacion()">
                <i class="fas fa-file-excel me-2"></i> Importar Excel
            </button>
            <button type="button" class="btn btn-primary btn-lg shadow-sm"
                onclick="alumno('agregar','-','{{ route('alumnos.store') }}')" title="registrar alumno">
                <i class="fas fa-user-plus me-2"></i> Registrar Alumno
            </button>
        </div>
    </div>

    {{-- Filtros --}}
    <div class="card shadow mb-4">
        <div class="card-body p-4">
            <form class="row g-3" method="GET" id="formFiltros">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Buscar</label>
                    <input type="text" class="form-control" name="buscar" id="buscar" 
                        placeholder="Código, nombre, apellido o documento" value="{{ request('buscar') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Grado</label>
                    <select class="form-select" name="grado_id" id="grado_id">
                        <option value="">Todos los grados</option>
                        @foreach($grados as $grado)
                            <option value="{{ $grado->id }}" {{ request('grado_id') == $grado->id ? 'selected' : '' }}>
                                {{ $grado->nombre }} - {{ $grado->sede->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Estado</label>
                    <select class="form-select" name="activo" id="activo">
                        <option value="">Todos</option>
                        <option value="1" {{ request('activo') === '1' ? 'selected' : '' }}>Activos</option>
                        <option value="0" {{ request('activo') === '0' ? 'selected' : '' }}>Inactivos</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100 me-2">
                        <i class="fas fa-search me-1"></i>Buscar
                    </button>
                    <a href="{{ route('alumnos.index') }}" class="btn btn-secondary">
                        <i class="fas fa-eraser"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table id="tableAlumnos" class="table table-hover display" style="width:100%">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 120px;">
                                <i class="fas fa-barcode me-2"></i>Código
                            </th>
                            <th><i class="fas fa-user me-2"></i>Nombres</th>
                            <th><i class="fas fa-user me-2"></i>Apellidos</th>
                            <th><i class="fas fa-graduation-cap me-2"></i>Grado</th>
                            <th><i class="fas fa-envelope me-2"></i>Email</th>
                            <th><i class="fas fa-phone me-2"></i>Teléfono</th>
                            <th class="text-center" style="width: 100px;">
                                <i class="fas fa-toggle-on me-2"></i>Estado
                            </th>
                            <th class="text-center" style="width: 150px;">
                                <i class="fas fa-cog me-2"></i>Acciones
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

{{-- Modal agregar/editar Alumno --}}
<div class="modal fade" id="modalAddAlumno" tabindex="-1" aria-labelledby="modalAlumnoTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="modalAlumnoTitle">
                    <i class="fas fa-user-plus me-2"></i>Registrar Alumno
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" id="form_save">
                @csrf
                <input type="hidden" name="_method" id="_method">
                <input type="hidden" id="input_accion">

                <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                    <h6 class="fw-bold mb-3 text-primary">Datos Básicos</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="codigo" class="form-label fw-semibold">
                                <i class="fas fa-barcode me-1 text-primary"></i>Código <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" name="codigo" id="codigo" 
                                placeholder="Ingrese el código del alumno" required>
                            <div class="form-text">Este código será usado para generar el QR</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="grado_id" class="form-label fw-semibold">
                                <i class="fas fa-graduation-cap me-1 text-primary"></i>Grado
                            </label>
                            <select class="form-select" name="grado_id" id="grado_id_form">
                                <option value="">Seleccione un grado</option>
                                @foreach($grados as $grado)
                                    <option value="{{ $grado->id }}">
                                        {{ $grado->nombre }} - {{ $grado->sede->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nombres" class="form-label fw-semibold">
                                <i class="fas fa-user me-1 text-primary"></i>Nombres <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" name="nombres" id="nombres" 
                                placeholder="Ingrese los nombres" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="apellidos" class="form-label fw-semibold">
                                <i class="fas fa-user me-1 text-primary"></i>Apellidos <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" name="apellidos" id="apellidos" 
                                placeholder="Ingrese los apellidos" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="fecha_nacimiento" class="form-label fw-semibold">
                                <i class="fas fa-calendar me-1 text-primary"></i>Fecha de Nacimiento
                            </label>
                            <input type="date" class="form-control" name="fecha_nacimiento" id="fecha_nacimiento">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="genero" class="form-label fw-semibold">
                                <i class="fas fa-venus-mars me-1 text-primary"></i>Género
                            </label>
                            <select class="form-select" name="genero" id="genero">
                                <option value="">Seleccione</option>
                                <option value="M">Masculino</option>
                                <option value="F">Femenino</option>
                                <option value="O">Otro</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="documento_identidad" class="form-label fw-semibold">
                                <i class="fas fa-id-card me-1 text-primary"></i>Documento
                            </label>
                            <input type="text" class="form-control" name="documento_identidad" id="documento_identidad" 
                                placeholder="Número de documento">
                        </div>
                    </div>

                    <hr class="my-4">
                    <h6 class="fw-bold mb-3 text-primary">Información de Contacto</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label fw-semibold">
                                <i class="fas fa-envelope me-1 text-primary"></i>Email
                            </label>
                            <input type="email" class="form-control" name="email" id="email" 
                                placeholder="correo@ejemplo.com">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="telefono" class="form-label fw-semibold">
                                <i class="fas fa-phone me-1 text-primary"></i>Teléfono
                            </label>
                            <input type="text" class="form-control" name="telefono" id="telefono" 
                                placeholder="3001234567">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="direccion" class="form-label fw-semibold">
                            <i class="fas fa-map-marker-alt me-1 text-primary"></i>Dirección
                        </label>
                        <textarea class="form-control" name="direccion" id="direccion" rows="2" 
                            placeholder="Dirección de residencia"></textarea>
                    </div>

                    <hr class="my-4">
                    <h6 class="fw-bold mb-3 text-primary">Datos del Acudiente</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nombre_acudiente" class="form-label fw-semibold">
                                <i class="fas fa-user-tie me-1 text-primary"></i>Nombre del Acudiente
                            </label>
                            <input type="text" class="form-control" name="nombre_acudiente" id="nombre_acudiente" 
                                placeholder="Nombre completo">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="telefono_acudiente" class="form-label fw-semibold">
                                <i class="fas fa-phone me-1 text-primary"></i>Teléfono del Acudiente
                            </label>
                            <input type="text" class="form-control" name="telefono_acudiente" id="telefono_acudiente" 
                                placeholder="3001234567">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="observaciones" class="form-label fw-semibold">
                            <i class="fas fa-sticky-note me-1 text-primary"></i>Observaciones
                        </label>
                        <textarea class="form-control" name="observaciones" id="observaciones" rows="3" 
                            placeholder="Notas adicionales sobre el alumno"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Cancelar
                    </button>
                    <button type="button" class="btn btn-primary" onclick="guardar()">
                        <i class="fas fa-save me-1"></i>Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal ver Alumno --}}
<div class="modal fade" id="modalShowAlumno" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-eye me-2"></i>Información del Alumno
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                <div class="text-center mb-4">
                    <div class="avatar-lg bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 80px; height: 80px; font-size: 2rem;">
                        <span id="avatar_initial">A</span>
                    </div>
                    <h5 id="s_full_name" class="mb-0"></h5>
                    <span class="badge bg-primary" id="s_codigo_badge"></span>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-muted">Grado</label>
                        <input type="text" class="form-control" id="s_grado" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-muted">Fecha de Nacimiento</label>
                        <input type="text" class="form-control" id="s_fecha_nacimiento" readonly>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-muted">Email</label>
                        <input type="text" class="form-control" id="s_email" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-muted">Teléfono</label>
                        <input type="text" class="form-control" id="s_telefono" readonly>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold text-muted">Dirección</label>
                    <input type="text" class="form-control" id="s_direccion" readonly>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-muted">Acudiente</label>
                        <input type="text" class="form-control" id="s_nombre_acudiente" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-muted">Teléfono Acudiente</label>
                        <input type="text" class="form-control" id="s_telefono_acudiente" readonly>
                    </div>
                </div>
                <div class="text-center mt-4">
                    <label class="form-label fw-semibold text-muted mb-3 d-block">Código QR</label>
                    <div class="bg-light p-3 rounded d-inline-block">
                        <img id="qr_alumno" class="img-fluid" alt="QR Code" width="300">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Modal Importación --}}
<div class="modal fade" id="modalImportacion" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-file-excel me-2"></i>Importar Alumnos desde Excel
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formImportacion" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Instrucciones:</strong>
                        <ul class="mb-0 mt-2">
                            <li>Descarga la plantilla para ver el formato requerido</li>
                            <li>Las columnas obligatorias son: código, nombres, apellidos</li>
                            <li>El sistema actualizará alumnos existentes si el código coincide</li>
                        </ul>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Seleccionar archivo Excel</label>
                        <input type="file" class="form-control" name="archivo" id="archivo_import" 
                            accept=".xlsx,.xls,.csv" required>
                        <div class="form-text">Formatos permitidos: .xlsx, .xls, .csv (máx. 10MB)</div>
                    </div>
                    <div class="text-center">
                        <a href="{{ route('alumnos.plantilla') }}" class="btn btn-outline-primary">
                            <i class="fas fa-download me-2"></i>Descargar Plantilla
                        </a>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-upload me-1"></i>Importar
                    </button>
                </div>
            </form>
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
        "pageLength": 25,
        "order": [[1, 'asc']],
        "columnDefs": [
            { "orderable": false, "targets": 7 }
        ]
    });

    $(document).ready(function() {
        loadTable(alumnos);
    })

    function abrirImportacion() {
        $('#formImportacion')[0].reset();
        $('#modalImportacion').modal('show');
    }

    $('#formImportacion').on('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        Swal.fire({
            title: 'Importando...',
            text: 'Por favor espere mientras se procesa el archivo',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        fetch('{{ route("alumnos.importar") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.res === 'ok') {
                let mensaje = `${data.message}\n\nImportados: ${data.importados}\nFallidos: ${data.fallidos}`;
                if (data.errors && data.errors.length > 0) {
                    mensaje += '\n\nErrores:\n' + data.errors.slice(0, 5).join('\n');
                    if (data.errors.length > 5) {
                        mensaje += `\n... y ${data.errors.length - 5} más`;
                    }
                }
                
                Swal.fire({
                    title: 'Importación Completada',
                    html: mensaje.replace(/\n/g, '<br>'),
                    icon: 'success',
                    confirmButtonText: 'Cerrar'
                });
                $('#modalImportacion').modal('hide');
                setTimeout(() => location.reload(), 2000);
            } else {
                Swal.fire('Error', data.message, 'error');
            }
        })
        .catch(error => {
            Swal.fire('Error', 'Ocurrió un error al importar', 'error');
        });
    });

    const alumno = (accion, url_show, url) => {
        input_accion.value = accion;
        if (accion == 'agregar') {
            $('#form_save')[0].reset();
            $('#modalAlumnoTitle').html('<i class="fas fa-user-plus me-2"></i>Registrar Alumno');
            $('#_method').val('POST');
        } else if (accion == 'editar') {
            show_alumno('edit', url_show);
            $('#modalAlumnoTitle').html('<i class="fas fa-user-edit me-2"></i>Editar Alumno');
            $('#_method').val('PUT');
        } else {
            show_alumno('show', url_show);
        }
        $('#form_save').attr('action', url);
        $('#modalAddAlumno').modal('show');
    }

    const save_alumno = () => {
        const formData = new FormData(document.getElementById('form_save'));
        const data = Object.fromEntries(formData);
        let url = $('#form_save').attr('action');
        
        Swal.fire({
            title: 'Guardando...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        fetch(url, {
            method: 'POST',
            body: JSON.stringify(data),
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        }).then(res => res.json())
        .then(response => {
            if (response.res == 'ok') {
                $('#modalAddAlumno').modal('hide');
                Swal.fire({
                    title: '¡Éxito!',
                    text: 'Registro guardado correctamente',
                    icon: 'success',
                    confirmButtonText: 'Cerrar',
                    timer: 2000,
                    timerProgressBar: true
                });
                setTimeout(() => location.reload(), 2000);
            } else {
                Swal.fire('Error', response.message || 'Error al guardar', 'error');
            }
        })
        .catch(error => {
            Swal.fire('Error', 'Ocurrió un error al guardar', 'error');
        });
    }

    const update_alumno = () => {
        const formData = new FormData(document.getElementById('form_save'));
        const data = Object.fromEntries(formData);
        let url = $('#form_save').attr('action');

        Swal.fire({
            title: 'Actualizando...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        fetch(url, {
            method: 'PUT',
            body: JSON.stringify(data),
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        }).then(res => res.json())
        .then(response => {
            if (response.res == 'ok') {
                $('#modalAddAlumno').modal('hide');
                Swal.fire({
                    title: '¡Éxito!',
                    text: 'Registro actualizado correctamente',
                    icon: 'success',
                    confirmButtonText: 'Cerrar',
                    timer: 2000,
                    timerProgressBar: true
                });
                setTimeout(() => location.reload(), 2000);
            } else {
                Swal.fire('Error', response.message || 'Error al actualizar', 'error');
            }
        })
        .catch(error => {
            Swal.fire('Error', 'Ocurrió un error al actualizar', 'error');
        });
    }

    const guardar = () => {
        if (codigo.value == '' || nombres.value == '' || apellidos.value == '') {
            Swal.fire({
                title: 'Campos incompletos',
                text: 'Por favor complete los campos obligatorios',
                icon: 'warning',
                confirmButtonText: 'Cerrar'
            });
        } else {
            if (input_accion.value == 'agregar') {
                save_alumno();
            } else if (input_accion.value == 'editar') {
                update_alumno();
            }
        }
    }

    const show_alumno = async (act, url) => {
        try {
            let alumno = await fetch(url)
                .then(response => response.json())
                .then(res => res);

            if (act == 'show') {
                $('#s_full_name').text(alumno.nombres + ' ' + alumno.apellidos);
                $('#s_codigo_badge').text(alumno.codigo);
                $('#s_grado').val(alumno.grado || '-');
                $('#s_fecha_nacimiento').val(alumno.fecha_nacimiento || '-');
                $('#s_email').val(alumno.email || '-');
                $('#s_telefono').val(alumno.telefono || '-');
                $('#s_direccion').val(alumno.direccion || '-');
                $('#s_nombre_acudiente').val(alumno.nombre_acudiente || '-');
                $('#s_telefono_acudiente').val(alumno.telefono_acudiente || '-');
                $('#qr_alumno').attr('src', `data:image/png;base64,${alumno.qr}`);
                $('#avatar_initial').text(alumno.nombres.charAt(0).toUpperCase());
                $('#modalShowAlumno').modal('show');
            } else {
                codigo.value = alumno.codigo;
                nombres.value = alumno.nombres;
                apellidos.value = alumno.apellidos;
                $('#grado_id_form').val(alumno.grado_id || '');
                $('#email').val(alumno.email || '');
                $('#telefono').val(alumno.telefono || '');
                $('#fecha_nacimiento').val(alumno.fecha_nacimiento || '');
                $('#genero').val(alumno.genero || '');
                $('#documento_identidad').val(alumno.documento_identidad || '');
                $('#direccion').val(alumno.direccion || '');
                $('#nombre_acudiente').val(alumno.nombre_acudiente || '');
                $('#telefono_acudiente').val(alumno.telefono_acudiente || '');
                $('#observaciones').val(alumno.observaciones || '');
            }
        } catch (error) {
            console.error('Error:', error);
            Swal.fire({
                title: 'Error',
                text: 'No se pudo cargar la información del alumno',
                icon: 'error',
                confirmButtonText: 'Cerrar'
            });
        }
    }

    const delete_alumno = url_delete => {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "Esta acción no se podrá revertir",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Eliminando...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                fetch(url_delete, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                }).then(res => res.json())
                .then(response => {
                    if (response.res == 'ok') {
                        Swal.fire({
                            title: '¡Eliminado!',
                            text: 'El alumno fue eliminado correctamente',
                            icon: 'success',
                            confirmButtonText: 'Cerrar',
                            timer: 2000,
                            timerProgressBar: true
                        });
                        setTimeout(() => location.reload(), 2000);
                    } else {
                        Swal.fire('Error', response.message || 'Error al eliminar', 'error');
                    }
                })
                .catch(error => {
                    Swal.fire('Error', 'Ocurrió un error al eliminar', 'error');
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
                `<span class="badge bg-primary">${alumno.codigo}</span>`,
                `<strong>${alumno.nombres}</strong>`,
                alumno.apellidos,
                alumno.grado ? `<span class="badge bg-info">${alumno.grado.nombre}</span>` : '-',
                alumno.email || '-',
                alumno.telefono || '-',
                alumno.activo ? '<span class="badge bg-success">Activo</span>' : '<span class="badge bg-secondary">Inactivo</span>',
                `   
                <div class="btn-group" role="group">
                    <button type="button" onclick="show_alumno('show','${ruta_show}')" 
                        class="btn btn-sm btn-info" title="Ver">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-warning"  
                        onclick="alumno('editar','${ruta_show}','${ruta_update}')" title="Editar">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-danger" 
                        onclick="delete_alumno('${ruta_destroy}')" title="Eliminar">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                `,
            ]).draw(false);
        });
    }

    const resetTable = () => {
        table.clear().draw();
    }
</script>

<style>
.avatar-lg {
    font-weight: 600;
}

/* Mejoras para el scroll del modal */
#modalAddAlumno .modal-body,
#modalShowAlumno .modal-body {
    max-height: 70vh;
    overflow-y: auto;
    padding-right: 1rem;
}

/* Scrollbar personalizado para modales */
#modalAddAlumno .modal-body::-webkit-scrollbar,
#modalShowAlumno .modal-body::-webkit-scrollbar {
    width: 8px;
}

#modalAddAlumno .modal-body::-webkit-scrollbar-track,
#modalShowAlumno .modal-body::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

#modalAddAlumno .modal-body::-webkit-scrollbar-thumb,
#modalShowAlumno .modal-body::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 10px;
}

#modalAddAlumno .modal-body::-webkit-scrollbar-thumb:hover,
#modalShowAlumno .modal-body::-webkit-scrollbar-thumb:hover {
    background: #555;
}

/* Asegurar que el modal tenga altura adecuada */
#modalAddAlumno .modal-dialog,
#modalShowAlumno .modal-dialog {
    max-height: 90vh;
    margin: 1.75rem auto;
}

#modalAddAlumno .modal-content,
#modalShowAlumno .modal-content {
    max-height: 90vh;
    display: flex;
    flex-direction: column;
}

#modalAddAlumno .modal-header,
#modalShowAlumno .modal-header {
    flex-shrink: 0;
}

#modalAddAlumno .modal-footer,
#modalShowAlumno .modal-footer {
    flex-shrink: 0;
}
</style>
@endsection
