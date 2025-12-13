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
        <nav class="navbar navbar-expand-lg navbar-dark bg-gradient-primary shadow-sm">
            <div class="container-fluid">
                <a class="navbar-brand fw-bold" href="{{ url('home') }}">
                    <i class="fas fa-clipboard-check me-2"></i>Control Asistencia
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        @if(auth()->user())
                            @php
                                $select = $select ?? '';
                            @endphp
                            <li class="nav-item">
                                <a class="nav-link {{$select == 'dashboard' ? 'active' : ''}}" href="{{ route('home') }}">
                                    <i class="fas fa-home me-1"></i> Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{$select == 'alumnos' ? 'active' : ''}}" href="{{ route('alumnos.index') }}">
                                    <i class="fas fa-users me-1"></i> Alumnos
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{$select == 'asistencias' ? 'active' : ''}}" href="{{ route('asistencias.index') }}">
                                    <i class="fas fa-file-signature me-1"></i> Asistencias
                                </a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle {{$select == 'configuracion' ? 'active' : ''}}" href="#" id="configDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-cog me-1"></i> Configuración
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="configDropdown">
                                    <li><a class="dropdown-item" href="{{ route('instituciones.index') }}">
                                        <i class="fas fa-building me-2"></i>Instituciones
                                    </a></li>
                                    <li><a class="dropdown-item" href="{{ route('sedes.index') }}">
                                        <i class="fas fa-map-marker-alt me-2"></i>Sedes
                                    </a></li>
                                    <li><a class="dropdown-item" href="{{ route('grados.index') }}">
                                        <i class="fas fa-graduation-cap me-2"></i>Grados
                                    </a></li>
                                </ul>
                            </li>
                            @yield('menu')
                        @endif
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link {{($select ?? '') == 'login' ? 'active' : ''}}" href="{{ route('login') }}">
                                        <i class="fas fa-sign-in-alt me-1"></i>{{ __('Login') }}
                                    </a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <div class="avatar-sm bg-white text-primary rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                        <span class="fw-bold">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                    </div>
                                    <span>{{ Auth::user()->name }}</span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt me-2"></i>{{ __('Logout') }}
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

        <main>
            @yield('content')
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
