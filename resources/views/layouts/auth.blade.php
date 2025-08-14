@include('sweetalert2::index')
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Systeme Management</title>
    @vite(['resources/scss/styles.scss', 'resources/js/app.js', 'resources/css/app.css'])
</head>

<body>

    <main class="d-flex justify-content-center align-items-center min-vh-100">
        @yield('content')
    </main>

</body>

</html>