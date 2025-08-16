@include('sweetalert2::index')

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Management</title>
    @vite(['resources/scss/style.scss', 'resources/js/app.js', 'resources/css/app.css'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>

<body class="d-flex position-relative">
    <aside class="d-flex flex-column hidden">
        <div class="d-flex justify-content-center">
            <div class="d-flex column-gap-1 align-items-center">
                <i class="bi bi-ui-radios-grid sidebar-item__icon"></i>
                <h1 class="h5 sidebar-item__label mb-0">System Management</h1>
            </div>
        </div>
        <div class="d-flex flex-grow-1 menu">
            <ul class="list-unstyled d-flex flex-column row-gap-1 flex-grow-1">
                <li class="sidebar-item d-flex align-items-center active">
                    <a href="" class="text-decoration-none d-flex column-gap-1">
                        <i class="bi bi-tag-fill cursor-pointer sidebar-item__icon"></i>
                        <span class="sidebar-item__label">Categories</span>
                    </a>
                </li>
                <li class="sidebar-item d-flex align-items-center">
                    <a href="" class="text-decoration-none d-flex column-gap-1">
                        <i class="bi bi-house-fill sidebar-item__icon"></i>
                        <span class="sidebar-item__label">Products</span>
                    </a>
                </li>
            </ul>
        </div>
    </aside>
    <div class="d-flex flex-column flex-grow-1">
        <header>
            <nav class="navbar bg-body-tertiary">
                <div class="container-fluid">
                    <span class="navbar-text">
                        <i class="bi bi-list" id="toggle-aside"></i>
                        @yield('title')
                    </span>
                </div>
            </nav>
        </header>
        <main class="container-fluid flex-grow-1">
            @yield('content')
        </main>
    </div>
</body>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const aside = document.querySelector('aside');
        const toggleAside = document.querySelector('#toggle-aside');

        toggleAside.addEventListener('click', function (e) {
            aside.classList.toggle('hidden')
        })

        window.addEventListener('resize', function () {
            if (window.innerWidth < 992) {
                document.addEventListener('click', function (e) {

                    if (!aside.contains(e.target) && !toggleAside.contains(e.target)) {
                        aside.classList.add('hidden')
                    }
                })
            }
        })


    })

</script>

</html>