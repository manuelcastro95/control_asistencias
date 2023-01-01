@extends('layouts.app')
@section('menu_select')
    {{$select = 'asistencias'}}
@endsection
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Control Asistencia</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{-- {{ __('You are logged in!') }} --}}
                    <div class="container-fluid">
                        <video id="preview" width="100%"></video>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    let scanner = new Instascan.Scanner(
        {
            video: document.getElementById('preview')
        }
    );
    scanner.addListener('scan', function(content) {

        fetch("{!!route('save.record')!!}", {
                method: 'POST', // or 'PUT'
                body: JSON.stringify({codigo: content}),
                headers:{
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }).then(res => res.json())
                .catch(error => console.error('Error:', error))
                    .then(response => {
                        // console.log('Success:', response)
                        Swal.fire({
                            title: response.msg,
                            text: '',
                            icon: response.level,
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'success',
                            confirmButtonText: 'cerrar'
                        });
                    });

    });
    
    Instascan.Camera.getCameras().then(cameras => 
    {
        if(cameras.length > 0){
            scanner.start(cameras[0]);
        } else {
            console.error("No hay cámara en el dispositivo.");
        }
    });
</script>
@endsection
