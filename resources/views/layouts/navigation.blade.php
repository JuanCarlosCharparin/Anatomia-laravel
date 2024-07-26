<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navigation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
</head>
<body>
<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
    <!-- Navbar Brand -->
    <a class="navbar-brand ps-3" href="{{ route('dashboard') }}">HU</a>
    <!-- Sidebar Toggle -->
    <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!">
        <i class="fas fa-bars"></i>
    </button>
    <!-- Navbar Search -->
    <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
        <div class="input-group">
            <input class="form-control" type="text" placeholder="Search for..." aria-label="Search for..." aria-describedby="btnNavbarSearch" />
            <button class="btn btn-primary" id="btnNavbarSearch" type="button">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </form>
    <!-- Navbar -->
    <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
        <!-- User Dropdown -->
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-user fa-fw"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profile</a></li>
                <li><a class="dropdown-item" href="#!">Settings</a></li>
                <li><a class="dropdown-item" href="#!">Activity Log</a></li>
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                </div>
                <li><hr class="dropdown-divider" /></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a class="dropdown-item" href="#!" onclick="event.preventDefault(); this.closest('form').submit();">Logout</a>
                    </form>
                </li>
            </ul>
        </li>
    </ul>
</nav>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="https://kit.fontawesome.com/a076d05399.js"></script>
</body>
</html>
