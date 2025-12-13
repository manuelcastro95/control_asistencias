@extends('layouts.app')

@section('titulo')
    Sedes
@endsection

@section('menu_select')
    {{$select = 'configuracion'}}
@endsection

@section('content')
<div class="container-fluid py-5 px-4">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h2 class="h3 mb-0 text-gray-800">
                <i class="fas fa-map-marker-alt me-2 text-primary"></i>Gestión de Sedes
            </h2>
            <p class="text-muted mb-0">Administra las sedes de las instituciones</p>
        </div>
        <button type="button" class="btn btn-primary btn-lg shadow-sm" onclick="sede('agregar')">
            <i class="fas fa-plus me-2"></i> Nueva Sede
        </button>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body p-4">
            <form class="row g-3" method="GET">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Filtrar por Institución</label>
                    <select class="form-select" name="institucion_id" onchange="this.form.submit()">
                        <option value="">Todas las instituciones</option>
                        @foreach($instituciones as $inst)
                            <option value="{{ $inst->id }}" {{ request('institucion_id') == $inst->id ? 'selected' : '' }}>
                                {{ $inst->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table id="tableSedes" class="table table-hover display" style="width:100%">
                    <thead>
                        <tr>
                            <th><i class="fas fa-building me-2"></i>Institución</th>
                            <th><i class="fas fa-map-marker-alt me-2"></i>Nombre</th>
                            <th><i class="fas fa-barcode me-2"></i>Código</th>
                            <th><i class="fas fa-location-dot me-2"></i>Dirección</th>
                            <th><i class="fas fa-phone me-2"></i>Teléfono</th>
                            <th class="text-center"><i class="fas fa-graduation-cap me-2"></i>Grados</th>
                            <th class="text-center"><i class="fas fa-toggle-on me-2"></i>Estado</th>
                            <th class="text-center" style="width: 120px;"><i class="fas fa-cog me-2"></i>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sedes as $sede)
                        <tr>
                            <td><strong>{{ $sede->institucion->nombre }}</strong></td>
                            <td>{{ $sede->nombre }}</td>
                            <td>{{ $sede->codigo ?? '-' }}</td>
                            <td>{{ $sede->direccion ?? '-' }}</td>
                            <td>{{ $sede->telefono ?? '-' }}</td>
                            <td>
                                <span class="badge bg-info">{{ $sede->grados->count() }} grado(s)</span>
                            </td>
                            <td class="text-center">
                                @if($sede->activo)
                                    <span class="badge bg-success">Activa</span>
                                @else
                                    <span class="badge bg-secondary">Inactiva</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-warning" 
                                        onclick="sede('editar', {{ $sede->id }})" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger" 
                                        onclick="eliminarSede({{ $sede->id }})" title="Eliminar">
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
<div class="modal fade" id="modalSede" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="modalSedeTitle">
                    <i class="fas fa-map-marker-alt me-2"></i>Nueva Sede
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formSede">
                @csrf
                <input type="hidden" name="_method" id="sede_method" value="POST">
                <input type="hidden" name="sede_id" id="sede_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Institución <span class="text-danger">*</span></label>
                        <select class="form-select" name="institucion_id" id="sede_institucion_id" required>
                            <option value="">Seleccione una institución</option>
                            @foreach($instituciones as $inst)
                                <option value="{{ $inst->id }}">{{ $inst->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label class="form-label fw-semibold">Nombre <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nombre" id="sede_nombre" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Código</label>
                            <input type="text" class="form-control" name="codigo" id="sede_codigo">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Dirección</label>
                        <input type="text" class="form-control" name="direccion" id="sede_direccion">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Teléfono</label>
                            <input type="text" class="form-control" name="telefono" id="sede_telefono">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" class="form-control" name="email" id="sede_email">
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
    let table = $('#tableSedes').DataTable({
        "language": {!! ConfigHelper::languageDataTable() !!},
        "pageLength": 10,
        "order": [[0, 'asc']]
    });

    function sede(accion, id = null) {
        if (accion === 'agregar') {
            $('#modalSedeTitle').html('<i class="fas fa-map-marker-alt me-2"></i>Nueva Sede');
            $('#sede_method').val('POST');
            $('#formSede')[0].reset();
            $('#sede_id').val('');
            $('#formSede').attr('action', '{{ route("sedes.store") }}');
        } else {
            fetch(`/configuracion/sedes/${id}`)
                .then(res => res.json())
                .then(data => {
                    $('#modalSedeTitle').html('<i class="fas fa-edit me-2"></i>Editar Sede');
                    $('#sede_method').val('PUT');
                    $('#sede_id').val(data.id);
                    $('#sede_institucion_id').val(data.institucion_id);
                    $('#sede_nombre').val(data.nombre);
                    $('#sede_codigo').val(data.codigo);
                    $('#sede_direccion').val(data.direccion);
                    $('#sede_telefono').val(data.telefono);
                    $('#sede_email').val(data.email);
                    $('#formSede').attr('action', `/configuracion/sedes/${id}`);
                });
        }
        $('#modalSede').modal('show');
    }

    $('#formSede').on('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const method = $('#sede_method').val();
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
                $('#modalSede').modal('hide');
                setTimeout(() => location.reload(), 1500);
            } else {
                Swal.fire('Error', data.message, 'error');
            }
        });
    });

    function eliminarSede(id) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "Esta acción no se podrá revertir",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/configuracion/sedes/${id}`, {
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

