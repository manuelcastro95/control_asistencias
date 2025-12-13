@extends('layouts.app')

@section('titulo')
    Instituciones
@endsection

@section('menu_select')
    {{$select = 'configuracion'}}
@endsection

@section('content')
<div class="container-fluid py-5 px-4">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h2 class="h3 mb-0 text-gray-800">
                <i class="fas fa-building me-2 text-primary"></i>Gestión de Instituciones
            </h2>
            <p class="text-muted mb-0">Administra las instituciones educativas</p>
        </div>
        <button type="button" class="btn btn-primary btn-lg shadow-sm" onclick="institucion('agregar')">
            <i class="fas fa-plus me-2"></i> Nueva Institución
        </button>
    </div>

    <div class="card shadow">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table id="tableInstituciones" class="table table-hover display" style="width:100%">
                    <thead>
                        <tr>
                            <th><i class="fas fa-building me-2"></i>Nombre</th>
                            <th><i class="fas fa-id-card me-2"></i>NIT</th>
                            <th><i class="fas fa-map-marker-alt me-2"></i>Dirección</th>
                            <th><i class="fas fa-phone me-2"></i>Teléfono</th>
                            <th class="text-center"><i class="fas fa-sitemap me-2"></i>Sedes</th>
                            <th class="text-center"><i class="fas fa-toggle-on me-2"></i>Estado</th>
                            <th class="text-center" style="width: 120px;"><i class="fas fa-cog me-2"></i>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($instituciones as $institucion)
                        <tr>
                            <td><strong>{{ $institucion->nombre }}</strong></td>
                            <td>{{ $institucion->nit ?? '-' }}</td>
                            <td>{{ $institucion->direccion ?? '-' }}</td>
                            <td>{{ $institucion->telefono ?? '-' }}</td>
                            <td>
                                <span class="badge bg-info">{{ $institucion->sedes->count() }} sede(s)</span>
                            </td>
                            <td class="text-center">
                                @if($institucion->activo)
                                    <span class="badge bg-success">Activa</span>
                                @else
                                    <span class="badge bg-secondary">Inactiva</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-warning" 
                                        onclick="institucion('editar', {{ $institucion->id }})" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger" 
                                        onclick="eliminarInstitucion({{ $institucion->id }})" title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Modal --}}
<div class="modal fade" id="modalInstitucion" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="modalInstitucionTitle">
                    <i class="fas fa-building me-2"></i>Nueva Institución
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formInstitucion">
                @csrf
                <input type="hidden" name="_method" id="institucion_method" value="POST">
                <input type="hidden" name="institucion_id" id="institucion_id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Nombre <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nombre" id="institucion_nombre" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">NIT</label>
                            <input type="text" class="form-control" name="nit" id="institucion_nit">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Dirección</label>
                        <input type="text" class="form-control" name="direccion" id="institucion_direccion">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Teléfono</label>
                            <input type="text" class="form-control" name="telefono" id="institucion_telefono">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" class="form-control" name="email" id="institucion_email">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let table = $('#tableInstituciones').DataTable({
        "language": {!! ConfigHelper::languageDataTable() !!},
        "pageLength": 10,
        "order": [[0, 'asc']]
    });

    function institucion(accion, id = null) {
        if (accion === 'agregar') {
            $('#modalInstitucionTitle').html('<i class="fas fa-building me-2"></i>Nueva Institución');
            $('#institucion_method').val('POST');
            $('#formInstitucion')[0].reset();
            $('#institucion_id').val('');
            $('#formInstitucion').attr('action', '{{ route("instituciones.store") }}');
        } else {
            // Cargar datos
            fetch(`/configuracion/instituciones/${id}`)
                .then(res => res.json())
                .then(data => {
                    $('#modalInstitucionTitle').html('<i class="fas fa-edit me-2"></i>Editar Institución');
                    $('#institucion_method').val('PUT');
                    $('#institucion_id').val(data.id);
                    $('#institucion_nombre').val(data.nombre);
                    $('#institucion_nit').val(data.nit);
                    $('#institucion_direccion').val(data.direccion);
                    $('#institucion_telefono').val(data.telefono);
                    $('#institucion_email').val(data.email);
                    $('#formInstitucion').attr('action', `/configuracion/instituciones/${id}`);
                });
        }
        $('#modalInstitucion').modal('show');
    }

    $('#formInstitucion').on('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const method = $('#institucion_method').val();
        const url = $(this).attr('action');

        fetch(url, {
            method: method,
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.res === 'ok') {
                Swal.fire('Éxito', data.message, 'success');
                $('#modalInstitucion').modal('hide');
                setTimeout(() => location.reload(), 1500);
            } else {
                Swal.fire('Error', data.message, 'error');
            }
        });
    });

    function eliminarInstitucion(id) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "Esta acción no se podrá revertir",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/configuracion/instituciones/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.res === 'ok') {
                        Swal.fire('Eliminado', data.message, 'success');
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        Swal.fire('Error', data.message, 'error');
                    }
                });
            }
        });
    }
</script>
@endsection

