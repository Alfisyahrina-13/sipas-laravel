<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk - SIPAS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-success-subtle d-flex align-items-center" style="min-height:100vh">
<div class="container" style="max-width:420px">
    <div class="card shadow-sm">
        <div class="card-body p-4">
            <h4 class="text-center text-success fw-bold">SIPAS</h4>
            <p class="text-center text-muted small">Sistem Informasi Pengelolaan Sampah dan Prioritas Pengangkutan (SAW)</p>
            <form method="POST" action="{{ route('login') }}">@csrf
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required autofocus>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="form-check mb-3"><input class="form-check-input" type="checkbox" name="remember" id="remember"><label class="form-check-label" for="remember">Ingat saya</label></div>
                <button class="btn btn-success w-100">Masuk</button>
            </form>
            <hr>
            <div class="small text-muted">Akun demo: <code>admin@sipas.test</code> / <code>petugas@sipas.test</code>, password <code>password</code></div>
        </div>
    </div>
</div>
</body>
</html>
