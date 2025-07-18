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
        <div class="body-wrapper">
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
