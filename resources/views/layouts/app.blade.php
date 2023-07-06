<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}.</title>


    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- DataTables -->
    <script src="//code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <script src="//cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

    <!-- DataTables -->
    <link rel="stylesheet" href="//cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">
    
    <script src="//cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="//cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="//cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
    <script src="//cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
    
    <link rel="preload" as="style" href="https://geolocalizzazionezanetti.app/build/assets/app.e9ed2bd0.css">
    <link rel="modulepreload" href="https://geolocalizzazionezanetti.app/build/assets/app.a032b13e.js">
    <link rel="stylesheet" href="https://geolocalizzazionezanetti.app/build/assets/app.e9ed2bd0.css">
    <script type="module" src="https://geolocalizzazionezanetti.app/build/assets/app.a032b13e.js"></script>

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- custom -->
    <link rel="stylesheet" href="/css/custom.css">
    <script>
        $(document).ready(function() {
            $('#zanetti-table, .zanetti-table').DataTable({
                dom: 'fltip',
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/it-IT.json',
                }
            });
        });
    </script>

    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

</head>

<body>
        @auth
        
        <nav class="navbar navbar-expand-md navbar-light shadow-sm px-5 py-2 bg-light">
        
            <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                <div class="logo_laravel">
                    <img src="{{ asset('build/assets/logo-zanetti-ambiente-9d418d94.png') }}" alt="Logo" class="w-75">
                </div>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>

            
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                
                <ul class="navbar-nav me-auto">
                    <li class="nav-item px-3">
                        <a class="nav-link" href="{{ route('items.index') }}"><i class="fas fa-grip-vertical"></i> {{ __('Caditoie') }}</a>
                    </li>
                    <li class="nav-item px-3">
                        <a class="nav-link" href="{{ route('users.index') }}"><i class="fas fa-users"></i> {{ __('Utenti/Clienti') }}</a>
                    </li>
                    <li class="nav-item px-3">
                        <a class="nav-link" href="{{ route('streets.index') }}"><i class="fas fa-road"></i> {{ __('Strade') }}</a>
                    </li>
                    <li class="nav-item px-3">
                        <a class="nav-link" href="{{ route('cities.index') }}"><i class="fas fa-city"></i> {{ __('Comuni') }}</a>
                    </li>
                    <li class="nav-item px-3">
                        <a class="nav-link" href="{{ route('tags.index', 'item') }}"><i class="fas fa-tags"></i> {{ __('Tags') }}</a>
                    </li>
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ml-auto">
                    <!-- Authentication Links -->
                    @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                    </li>
                    @else
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            <i class="fas fa-users-cog"></i> {{ Auth::user()->name }}
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ url('dashboard') }}">{{__('Dashboard')}}</a>
                            <a class="dropdown-item" href="{{ url('profile') }}">{{__('Profile')}}</a>
                            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
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
        </nav>
        @endauth

        <main>
            @yield('content')
        </main>
</body>

</html>
