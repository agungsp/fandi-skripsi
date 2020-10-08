<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        @yield('meta')
        <title>@yield('title')</title>
        <link href="{{ asset('sb-admin/css/styles.css') }}" rel="stylesheet" />
        <link href="{{ asset('fontawesome/css/all.min.css') }}" rel="stylesheet" />
        <link href="{{ asset('css/custom.css') }}" rel="stylesheet" />
        @yield('css')
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <a class="navbar-brand" href="#">{{ env('APP_NAME', 'Laravel') }}</a>
            <button class="btn btn-link btn-sm d-none d-lg-block text-light" id="sidebarToggle" role="button"><i class="fas fa-bars"></i></button>
            <button class="btn btn-link btn-sm d-block d-lg-none ml-auto text-light" id="sidebarToggle2" role="button"><i class="fas fa-bars"></i></button>
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-light" id="sidenavAccordion">
                    <div class="sb-sidenav-menu" style="overflow-x: hidden;">
                        <div class="row mt-3">
                            <div class="col d-flex justify-content-center">
                                <img src="https://api.adorable.io/avatars/96/abott@adorable.png" alt="avatar" class="img-thumbnail rounded-circle" style="max-width: 70px;">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col d-flex justify-content-center">
                                <span>Username</span>
                            </div>
                        </div>
                        <hr>
                        <div class="nav">
                            <a class="nav-link" href="#">
                                <div class="sb-nav-link-icon"><i class="fas fa-list-ol"></i></div>
                                Transaksi
                            </a>
                        </div>
                        <div class="nav">
                            <a class="nav-link" href="#">
                                <div class="sb-nav-link-icon"><i class="fas fa-chart-pie"></i></div>
                                Analisa
                            </a>
                        </div>
                    </div>
                    <div class="sb-sidenav-footer mb-3 ">
                        <form id="logout-form" action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="btn btn-danger btn-sm btn-block rounded-pill" type="submit" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Logout
                            </button>
                        </form>

                    </div>
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid">
                        <h4 class="mt-3">@yield('title-content')Title</h4>
                        <hr>
                        @yield('content')
                    </div>
                </main>
                <footer class="py-2 mt-auto">
                    <div class="container-fluid">
                        <div class="d-flex align-items-center justify-content-center small">
                            <div>Copyright &copy; {{ env('APP_NAME', 'Laravel') }} {{ date('Y') }}</div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        @yield('modal')
        <script src="{{ asset('js/jquery.min.js') }}" crossorigin="anonymous"></script>
        <script src="{{ asset('bootstrap/js/bootstrap.bundle.min.js') }}" crossorigin="anonymous"></script>
        <script src="{{ asset('sb-admin/js/scripts.js') }}"></script>
        @yield('js')
    </body>
</html>
