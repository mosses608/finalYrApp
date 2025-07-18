<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Blockchain-Integrated | Waste System</title>
    <link rel="shortcut icon" type="image/png" href="{{ asset('/assets/images/logos/logo.png') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/styles.min.css') }}" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://js.pusher.com/beams/2.1.0/push-notifications-cdn.js"></script>
</head>

<body>
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">
        <div class="app-topstrip bg-dark py-6 px-3 w-100 d-lg-flex align-items-center justify-content-between"
            id="app-topstrip">
            <div class="d-flex align-items-center justify-content-center gap-5 mb-2 mb-lg-0">
                <a class="d-flex justify-content-center" href="#">
                    <img src="{{ asset('/assets/images/logos/logo.png') }}" alt="" class="rounded-circle"
                        width="100" height="40" />
                </a>
            </div>
            <div class="d-lg-flex align-items-center gap-2">
                <h3 class="text-white mb-2 mb-lg-0 fs-5 text-center">Blockchain-Integrated Waste Management and
                    Recycling Exchange System</h3>
                <div class="d-flex align-items-center justify-content-center gap-2">

                    <div class="dropdown d-flex">
                        <a class="btn btn-primary d-flex align-items-center gap-1"
                            href="{{ route('logout.invalidate') }}">
                            Logout
                            <i class="ti ti-logout fs-5"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @include('partials.nav')
        <div class="body-wrapper">
            <header class="app-header">
                <nav class="navbar navbar-expand-lg navbar-light">
                    <ul class="navbar-nav">
                        <li class="nav-item d-block d-xl-none">
                            <a class="nav-link sidebartoggler " id="headerCollapse" href="javascript:void(0)">
                                <i class="ti ti-menu-2"></i>
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link" href="/manage-complaints" id="blinking">
                                <i class="ti ti-bell"></i>
                                <div class="notification bg-primary rounded-circle"></div>
                            </a>
                            <style>
                                #blinking {
                                    animation: blinker 1s linear infinite;
                                    color: red;
                                }

                                @keyframes blinker {
                                    50% {
                                        opacity: 0;
                                    }
                                }
                            </style>
                            <div class="dropdown-menu dropdown-menu-animate-up" aria-labelledby="drop1">
                                <div class="message-body">
                                    <a href="javascript:void(0)" class="dropdown-item">
                                        Item 1
                                    </a>
                                    <a href="javascript:void(0)" class="dropdown-item">
                                        Item 2
                                    </a>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
                        <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">

                            <li class="nav-item dropdown">
                                <div class="row">
                                    @if (Auth::user()->user_type != 1)
                                        <div class="col-8">
                                            <a href="{{ route('my.wallet') }}"
                                                class="btn btn-primary p-2 mt-3 btn-sm">Wallet:
                                                <strong>{{ $balance->currency . ' ' . number_format($balance->balance, 2) }}</strong></a>
                                        </div>
                                    @endif
                                    @if (Auth::user()->user_type === 1)
                                        <div class="col-4">
                                            <a class="nav-link " href="{{ route('admin.profile') }}" id="drop2">
                                                <img src="{{ asset('/assets/images/profile/user-1.jpg') }}"
                                                    alt="" width="35" height="35" class="rounded-circle">
                                            </a>
                                        </div>
                                    @else
                                        <div class="col-4">
                                            <a class="nav-link " href="{{ route('profile') }}" id="drop2">
                                                <img src="{{ asset('/assets/images/profile/user-1.jpg') }}"
                                                    alt="" width="35" height="35" class="rounded-circle">
                                            </a>
                                        </div>
                                    @endif
                                </div>
                                <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up"
                                    aria-labelledby="drop2">
                                    <div class="message-body">
                                        <a href="javascript:void(0)"
                                            class="d-flex align-items-center gap-2 dropdown-item">
                                            <i class="ti ti-user fs-6"></i>
                                            <p class="mb-0 fs-3">My Profile</p>
                                        </a>
                                        <a href="javascript:void(0)"
                                            class="d-flex align-items-center gap-2 dropdown-item">
                                            <i class="ti ti-mail fs-6"></i>
                                            <p class="mb-0 fs-3">My Account</p>
                                        </a>
                                        <a href="javascript:void(0)"
                                            class="d-flex align-items-center gap-2 dropdown-item">
                                            <i class="ti ti-list-check fs-6"></i>
                                            <p class="mb-0 fs-3">My Task</p>
                                        </a>
                                        <a href="./authentication-login.html"
                                            class="btn btn-outline-primary mx-3 mt-2 d-block">Logout</a>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>
            </header>
            <main class="body-wrapper-inner">
                @yield('content')
            </main>
        </div>
    </div>

</body>
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script src="{{ asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
<!-- solar icons -->
<script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
<script src="{{ asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/js/sidebarmenu.js') }}"></script>
<script src="{{ asset('assets/js/app.min.js') }}"></script>
<script src="{{ asset('assets/libs/apexcharts/dist/apexcharts.min.js') }}"></script>
<script src="{{ asset('assets/libs/simplebar/dist/simplebar.js') }}"></script>
<script src="{{ asset('assets/js/dashboard.js') }}"></script>
<!-- solar icons -->
<script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>


<style>
    @media(max-width: 768px) {
        #app-topstrip {
            display: none !important;
        }

        /* #body-wrapper{
            margin-top: 0% !important;
            margin-left: 0% !important;
            width: 100% !important;
        } */
    }
</style>

<script src="https://js.pusher.com/7.2/pusher.min.js"></script>

<script>
    Echo.private('user.{{ Auth::id() }}')
        .notification((notification) => {
            alert(notification.title + ": " + notification.body);
        });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log("Page loaded");
        setTimeout(function() {
            console.log("Starting location tracking...");

            if (navigator.geolocation) {
                navigator.geolocation.watchPosition(function(position) {
                    console.log("Got position:", position.coords);

                    fetch('/update-location', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                latitude: position.coords.latitude,
                                longitude: position.coords.longitude
                            })
                        })
                        .then(response => response.json())
                        .then(data => console.log("Location sent:", data))
                        .catch(error => console.error("Error sending location:", error));
                }, function(error) {
                    console.error("Geolocation error:", error);
                }, {
                    enableHighAccuracy: true,
                    maximumAge: 10000,
                    timeout: 5000
                });
            } else {
                console.warn("Geolocation is not supported by this browser.");
            }

        }, 1500);
    });
</script>

</html>
