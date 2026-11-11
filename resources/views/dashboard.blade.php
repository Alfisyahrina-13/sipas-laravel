@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
<h4 class="mb-3">Dashboard</h4>
<div class="row g-3 mb-4">
    @foreach([['Lokasi / TPS', $jumlahLokasi, 'bi-geo-alt', 'success'], ['Armada', $jumlahArmada, 'bi-truck', 'primary'], ['Data Sampah', $jumlahData, 'bi-clipboard-data', 'warning'], ['Penugasan Aktif', $jadwal, 'bi-calendar-check', 'danger']] as [$label, $n, $icon, $c])
        <div class="col-6 col-md-3">
            <div class="card border-{{ $c }} h-100"><div class="card-body">
                <div class="text-muted small"><i class="bi {{ $icon }}"></i> {{ $label }}</div>
                <div class="fs-2 fw-bold text-{{ $c }}">{{ $n }}</div>
            </div></div>
        </div>
    @endforeach
</div>

<div class="row g-3">
    <div class="col-lg-7">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between"><strong>5 Prioritas Pengangkutan Teratas</strong><a href="{{ route('saw.index') }}" class="small">Lihat perhitungan</a></div>
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead><tr><th>Rank</th><th>Lokasi</th><th class="text-end">Nilai V</th></tr></thead>
                    <tbody>
                    @forelse($top as $r)
                        <tr><td><span class="badge bg-success">{{ $r['rank'] }}</span></td><td>{{ $r['location']->nama_lokasi }}</td><td class="text-end">{{ number_format($r['v'], 4, ',', '.') }}</td></tr>
                    @empty
                        <tr><td colspan="3" class="text-center text-muted py-3">Belum ada hasil. Pastikan total bobot kriteria = 1 dan data sampah sudah diinput.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card h-100">
            <div class="card-header"><strong>Penugasan Terbaru</strong></div>
            <ul class="list-group list-group-flush">
                @forelse($terbaru as $p)
                    <li class="list-group-item d-flex justify-content-between">
                        <span>{{ $p->location->nama_lokasi }}<br><small class="text-muted">{{ $p->vehicle->nama_armada }} - {{ $p->tanggal_angkut->format('d/m/Y') }}</small></span>
                        <span class="badge align-self-center bg-{{ $p->status === 'Selesai' ? 'success' : ($p->status === 'Dalam Proses' ? 'warning' : 'secondary') }}">{{ $p->status }}</span>
                    </li>
                @empty
                    <li class="list-group-item text-muted">Belum ada penugasan.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection
