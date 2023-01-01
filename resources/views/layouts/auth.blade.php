<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
    
        <title>Control Asistencia - @yield('titulo')</title>
        <!-- Favicon -->
        <link rel="shortcut icon" href="{{ asset('admin/img/favicon.ico') }}" />
    
        <link rel="stylesheet" href="{{ asset('admin/plugins/dataTables/datatables.min.css') }}">
        <!-- CSS -->
        <link rel="stylesheet" href="{{asset('admin/css/auth.css')}}">
        <!-- Fonts -->
        <link rel="dns-prefetch" href="//fonts.gstatic.com">
        <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
        <!-- Scripts -->
        @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    
    </head>
<body>
    <main class="py-4">
        <div class="container">
            @yield('contenido')
        </div>
    </main>
    <!-- JQuery -->
    <script src="{{ asset('admin/plugins/JQuery/jquery.min.js')}}"></script>
</body>
</html>