@extends('layouts.app')

@section('titulo')
    Grados
@endsection

@section('menu_select')
    {{$select = 'configuracion'}}
@endsection

@section('content')
<div class="container-fluid py-5 px-4">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h2 class="h3 mb-0 text-gray-800">
                <i class="fas fa-graduation-cap me-2 text-primary"></i>Gestión de Grados
            </h2>
            <p class="text-muted mb-0">Administra los grados académicos</p>
        </div>
        <button type="button" class="btn btn-primary btn-lg shadow-sm" onclick="grado('agregar')">
            <i class="fas fa-plus me-2"></i> Nuevo Grado
        </button>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body p-4">
            <form class="row g-3" method="GET">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Filtrar por Sede</label>
                    <select class="form-select" name="sede_id" onchange="this.form.submit()">
                        <option value="">Todas las sedes</option>
                        @foreach($sedes as $sede)
                            <option value="{{ $sede->id }}" {{ request('sede_id') == $sede->id ? 'selected' : '' }}>
                                {{ $sede->institucion->nombre }} - {{ $sede->nombre }}
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
                <table id="tableGrados" class="table table-hover display" style="width:100%">
                    <thead>
                        <tr>
                            <th><i class="fas fa-building me-2"></i>Institución</th>
                            <th><i class="fas fa-map-marker-alt me-2"></i>Sede</th>
                            <th><i class="fas fa-graduation-cap me-2"></i>Nombre</th>
                            <th><i class="fas fa-barcode me-2"></i>Código</th>
                            <th class="text-center"><i class="fas fa-sort-numeric-up me-2"></i>Orden</th>
                            <th class="text-center"><i class="fas fa-users me-2"></i>Alumnos</th>
                            <th class="text-center"><i class="fas fa-toggle-on me-2"></i>Estado</th>
                            <th class="text-center" style="width: 120px;"><i class="fas fa-cog me-2"></i>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($grados as $grado)
                        <tr>
                            <td><strong>{{ $grado->sede->institucion->nombre }}</strong></td>
                            <td>{{ $grado->sede->nombre }}</td>
                            <td>{{ $grado->nombre }}</td>
                            <td>{{ $grado->codigo ?? '-' }}</td>
                            <td>{{ $grado->orden }}</td>
                            <td>
                                <span class="badge bg-primary">{{ $grado->alumnos->count() }} alumno(s)</span>
                            </td>
                            <td class="text-center">
                                @if($grado->activo)
                                    <span class="badge bg-success">Activo</span>
                                @else
                                    <span class="badge bg-secondary">Inactivo</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-warning" 
                                        onclick="grado('editar', {{ $grado->id }})" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger" 
                                        onclick="eliminarGrado({{ $grado->id }})" title="Eliminar">
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
<div class="modal fade" id="modalGrado" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="modalGradoTitle">
                    <i class="fas fa-graduation-cap me-2"></i>Nuevo Grado
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formGrado">
                @csrf
                <input type="hidden" name="_method" id="grado_method" value="POST">
                <input type="hidden" name="grado_id" id="grado_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Sede <span class="text-danger">*</span></label>
                        <select class="form-select" name="sede_id" id="grado_sede_id" required>
                            <option value="">Seleccione una sede</option>
                            @foreach($sedes as $sede)
                                <option value="{{ $sede->id }}" data-institucion="{{ $sede->institucion->nombre }}">
                                    {{ $sede->institucion->nombre }} - {{ $sede->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Nombre <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nombre" id="grado_nombre" 
                                placeholder="Ej: Primero, Segundo" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-semibold">Código</label>
                            <input type="text" class="form-control" name="codigo" id="grado_codigo" 
                                placeholder="Ej: 1°, 2°">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-semibold">Orden</label>
                            <input type="number" class="form-control" name="orden" id="grado_orden" 
                                value="0" min="0">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Descripción</label>
                        <textarea class="form-control" name="descripcion" id="grado_descripcion" rows="3"></textarea>
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
    let table = $('#tableGrados').DataTable({
        "language": {!! ConfigHelper::languageDataTable() !!},
        "pageLength": 10,
        "order": [[4, 'asc']]
    });

    function grado(accion, id = null) {
        if (accion === 'agregar') {
            $('#modalGradoTitle').html('<i class="fas fa-graduation-cap me-2"></i>Nuevo Grado');
            $('#grado_method').val('POST');
            $('#formGrado')[0].reset();
            $('#grado_orden').val('0');
            $('#grado_id').val('');
            $('#formGrado').attr('action', '{{ route("grados.store") }}');
        } else {
            fetch(`/configuracion/grados/${id}`)
                .then(res => res.json())
                .then(data => {
                    $('#modalGradoTitle').html('<i class="fas fa-edit me-2"></i>Editar Grado');
                    $('#grado_method').val('PUT');
                    $('#grado_id').val(data.id);
                    $('#grado_sede_id').val(data.sede_id);
                    $('#grado_nombre').val(data.nombre);
                    $('#grado_codigo').val(data.codigo);
                    $('#grado_orden').val(data.orden);
                    $('#grado_descripcion').val(data.descripcion);
                    $('#formGrado').attr('action', `/configuracion/grados/${id}`);
                });
        }
        $('#modalGrado').modal('show');
    }

    $('#formGrado').on('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const method = $('#grado_method').val();
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
                $('#modalGrado').modal('hide');
                setTimeout(() => location.reload(), 1500);
            } else {
                Swal.fire('Error', data.message, 'error');
            }
        });
    });

    function eliminarGrado(id) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "Esta acción no se podrá revertir",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/configuracion/grados/${id}`, {
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

