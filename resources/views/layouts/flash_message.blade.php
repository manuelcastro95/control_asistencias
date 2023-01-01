@foreach (session('flash_notification', collect())->toArray() as $message)
    <script type="text/javascript">
        Swal.fire({
            icon: "{!! $message['level'] !!}",
            title: 'Notificación',
            text: "{!! $message['message'] !!}",
            type: "{!! $message['level'] !!}",
            confirmButtonText: "Cerrar"
        })
    </script>
@endforeach

{{ session()->forget('flash_notification') }}
