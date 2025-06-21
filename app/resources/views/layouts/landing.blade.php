<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login | Welcome</title>
    {{-- <link rel="shortcut icon" type="image/png" href="./assets/images/logos/favicon.png" /> --}}
    <link rel="stylesheet" href="{{ asset('assets/css/styles.min.css') }}" />
</head>

<body>
    <main>
        @yield('content')
    </main>
</body>
<script src="{{ asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
<!-- solar icons -->
<script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>

</html>
