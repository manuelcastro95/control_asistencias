@extends('layouts.app')

@section('titulo')
    Dashboard
@endsection

@section('menu_select')
    {{$select = 'dashboard'}}
@endsection

@section('content')
<div class="container-fluid py-5 px-4">
    <!-- Estadísticas principales -->
    <div class="row mb-5">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100">
                <div class="card-body p-4">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Alumnos
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalAlumnos }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100">
                <div class="card-body p-4">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Asistencias
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalAsistencias }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-check fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100">
                <div class="card-body p-4">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Asistencias Hoy
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $asistenciasHoy }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-day fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100">
                <div class="card-body p-4">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Porcentaje Hoy
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($porcentajeHoy, 1) }}%
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-percentage fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos y contenido -->
    <div class="row mb-4">
        <!-- Gráfico de asistencias últimos 7 días -->
        <div class="col-xl-8 col-lg-7 mb-4">
            <div class="card shadow">
                <div class="card-header py-4 px-4 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Asistencias - Últimos 7 Días</h6>
                </div>
                <div class="card-body p-4">
                    <canvas id="asistenciasChart" height="80"></canvas>
                </div>
            </div>
        </div>

        <!-- Top Alumnos -->
        <div class="col-xl-4 col-lg-5 mb-4">
            <div class="card shadow">
                <div class="card-header py-4 px-4 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Top 5 Alumnos</h6>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 60px;" class="text-center">#</th>
                                    <th><i class="fas fa-user me-2"></i>Alumno</th>
                                    <th class="text-center"><i class="fas fa-clipboard-check me-2"></i>Asistencias</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topAlumnos as $index => $alumno)
                                <tr>
                                    <td class="text-center">
                                        <span class="badge bg-primary" style="font-size: 0.875rem; padding: 0.5rem 0.75rem;">
                                            {{ $index + 1 }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 36px; height: 36px; font-weight: 600;">
                                                <span class="text-white">{{ substr($alumno->nombres, 0, 1) }}</span>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-gray-800">{{ $alumno->full_name }}</div>
                                                <small class="text-muted"><i class="fas fa-barcode me-1"></i>{{ $alumno->codigo }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-success" style="font-size: 0.875rem; padding: 0.5rem 0.75rem;">
                                            {{ $alumno->asistencias_count }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">
                                        <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                        No hay datos disponibles
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Escáner QR -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-4 px-4">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-qrcode me-2"></i>Registrar Asistencia
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-6 mx-auto">
                            <div class="scanner-container position-relative mb-4">
                                <video id="preview" class="w-100 rounded shadow" style="max-height: 400px; object-fit: cover;"></video>
                                <div class="scanner-overlay position-absolute top-50 start-50 translate-middle">
                                    <div class="scanner-frame"></div>
                                </div>
                            </div>
                            <div class="text-center mt-4">
                                <p class="text-muted mb-0">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Escanea el código QR del alumno para registrar su asistencia
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    // Configurar gráfico de asistencias
    const ctx = document.getElementById('asistenciasChart');
    const asistenciasData = @json($asistenciasUltimos7Dias);
    
    const labels = asistenciasData.map(item => {
        const date = new Date(item.fecha);
        return date.toLocaleDateString('es-ES', { weekday: 'short', day: 'numeric' });
    });
    
    const data = asistenciasData.map(item => item.total);
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Asistencias',
                data: data,
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

    // Configurar escáner QR
    let scanner = new Instascan.Scanner({
        video: document.getElementById('preview'),
        scanPeriod: 5,
        mirror: false
    });

    scanner.addListener('scan', function(content) {
        fetch("{{ route('save.record') }}", {
            method: 'POST',
            body: JSON.stringify({codigo: content}),
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(res => res.json())
        .then(response => {
            Swal.fire({
                title: response.msg,
                icon: response.level,
                showConfirmButton: true,
                confirmButtonText: 'Cerrar',
                timer: 3000,
                timerProgressBar: true
            });
            
            // Recargar página después de 2 segundos si fue exitoso
            if (response.level === 'success') {
                setTimeout(() => {
                    location.reload();
                }, 2000);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                title: 'Error',
                text: 'Ocurrió un error al registrar la asistencia',
                icon: 'error',
                confirmButtonText: 'Cerrar'
            });
        });
    });

    // Inicializar cámara
    Instascan.Camera.getCameras().then(function(cameras) {
        if (cameras.length > 0) {
            scanner.start(cameras[0]);
        } else {
            Swal.fire({
                title: 'Cámara no encontrada',
                text: 'No se detectó ninguna cámara en el dispositivo',
                icon: 'warning',
                confirmButtonText: 'Cerrar'
            });
        }
    }).catch(function(e) {
        console.error(e);
        Swal.fire({
            title: 'Error de cámara',
            text: 'No se pudo acceder a la cámara',
            icon: 'error',
            confirmButtonText: 'Cerrar'
        });
    });
</script>

<style>
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}
.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}
.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}
.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}

.scanner-container {
    position: relative;
    background: #000;
    border-radius: 8px;
    overflow: hidden;
}

.scanner-overlay {
    pointer-events: none;
}

.scanner-frame {
    width: 250px;
    height: 250px;
    border: 3px solid #4e73df;
    border-radius: 8px;
    box-shadow: 0 0 0 9999px rgba(0, 0, 0, 0.5);
}

.avatar-sm {
    font-size: 0.875rem;
}
</style>
@endsection
