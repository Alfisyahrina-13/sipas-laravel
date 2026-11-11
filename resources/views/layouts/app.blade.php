<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'SIPAS') - Pengelolaan Sampah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-success mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route('dashboard') }}"><i class="bi bi-recycle"></i> SIPAS</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu"><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="menu">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">Dashboard</a></li>
                @if(auth()->user()->isAdmin())
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('locations.*') ? 'active' : '' }}" href="{{ route('locations.index') }}">Lokasi</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('vehicles.*') ? 'active' : '' }}" href="{{ route('vehicles.index') }}">Armada</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('criterias.*') ? 'active' : '' }}" href="{{ route('criterias.index') }}">Kriteria</a></li>
                @endif
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('records.*') ? 'active' : '' }}" href="{{ route('records.index') }}">Data Sampah</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('saw.*') ? 'active' : '' }}" href="{{ route('saw.index') }}">Perhitungan SAW</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('pickups.*') ? 'active' : '' }}" href="{{ route('pickups.index') }}">Penugasan</a></li>
            </ul>
            <span class="navbar-text me-3">{{ auth()->user()->name }} <span class="badge bg-light text-success">{{ auth()->user()->role }}</span></span>
            <form method="POST" action="{{ route('logout') }}">@csrf
                <button class="btn btn-sm btn-outline-light">Keluar</button>
            </form>
        </div>
    </div>
</nav>

<main class="container pb-5">
    @if(session('success'))<div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button class="btn-close" data-bs-dismiss="alert"></button></div>@endif
    @if(session('error'))<div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}<button class="btn-close" data-bs-dismiss="alert"></button></div>@endif
    @if($errors->any())
        <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
    @endif
    @yield('content')
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
