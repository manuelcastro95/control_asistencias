<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Control Asistencia - @yield('titulo')</title>
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('admin/img/favicon.ico') }}" />

    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('admin/plugins/select2/select2.min.css') }}">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('admin/plugins/dataTables/datatables.min.css') }}">
    <!-- CSS -->
    <link rel="stylesheet" href="{{asset('admin/css/style.css')}}">
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('home') }}">
                    Control Asistencia
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        @if(auth()->user())
                            @if($select != '')
                                <li class="nav-item">
                                    <a class="nav-link  {{$select != 'alumnos' ?:'active'}}" href="{{ route('alumnos.index') }}"><i class="fas fa-users"></i> Alumnos</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link  {{$select != 'asistencias' ?:'active'}}" href="{{ route('asistencias.index') }}"><i class="fas fa-file-signature"></i> Asistencias</a>
                                </li>
                                @yield('menu')
                            @endif
                        @endif
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link {{$select != 'login' ?:'active'}}" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                {{-- <li class="nav-item">
                                    <a class="nav-link {{$select != 'register' ?:'active'}}" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li> --}}
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            <div class="container">
                @yield('content')
            </div>
        </main>
        
    </div>
    <!-- JQuery -->
    <script src="{{ asset('admin/plugins/JQuery/jquery.min.js')}}"></script>
    <!-- Select2 -->
    <script src="{{ asset('admin/plugins/select2/select2.min.js') }}"></script>
    <script src="{{ asset('admin/js/config.js') }}"></script>

    <!-- DataTables -->
    <script src="{{asset('admin/plugins/dataTables/datatables.min.js')}}"></script>
    <script src="{{asset('admin/plugins/dataTables/pdfmake.min.js')}}"></script>
    <script src="{{asset('admin/plugins/dataTables/vfs_fonts.js')}}"></script>

    <!-- Instascan -->
    <script type="text/javascript" src="{{ asset('admin/plugins/instascan/instascan.min.js') }}" ></script>	
    
    <!-- Sweet Alert2 -->
    <script src="{{asset('admin/plugins/sweet_alert/sweetalert2@11.js')}}"></script>
    @include('layouts.flash_message')
    
    <!-- FontAwesome 5.13 -->
    <script src="{{asset('admin/plugins/font_awesome/fontawesome.js')}}"></script>
    @yield('scripts')
</body>
</html>
