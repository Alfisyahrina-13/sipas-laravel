@extends('layouts.app')
@section('title', 'Perhitungan SAW')
@section('content')
@php $fmt = fn($n, $d = 4) => number_format($n, $d, ',', '.'); $cr = $hasil['criterias']; @endphp
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Perhitungan SAW - Prioritas Pengangkutan</h4>
    @if(!empty($hasil['rows']))
        <form method="POST" action="{{ route('saw.simpan') }}">@csrf<button class="btn btn-outline-success"><i class="bi bi-save"></i> Simpan Hasil Hari Ini</button></form>
    @endif
</div>

@if(!$hasil['valid'])
    <div class="alert alert-warning">Perhitungan belum dapat dijalankan karena total bobot kriteria = <strong>{{ $hasil['totalBobot'] }}</strong> (harus 1).
        @if(auth()->user()->isAdmin())<a href="{{ route('criterias.index') }}">Atur kriteria</a>@else Hubungi admin.@endif</div>
@elseif(empty($hasil['rows']))
    <div class="alert alert-info">Belum ada data sampah. <a href="{{ route('records.create') }}">Input data sampah</a> terlebih dahulu.</div>
@else
    <div class="card mb-4"><div class="card-header bg-success text-white"><strong>Hasil Akhir: Peringkat Prioritas</strong></div>
    <div class="table-responsive"><table class="table table-striped mb-0">
        <thead><tr><th>Peringkat</th><th>Lokasi</th><th class="text-end">Nilai V</th><th></th></tr></thead>
        <tbody>
        @foreach($hasil['rows'] as $r)
            <tr>
                <td><span class="badge bg-{{ $r['rank'] === 1 ? 'danger' : 'secondary' }} fs-6">{{ $r['rank'] }}</span></td>
                <td>{{ $r['location']->nama_lokasi }}</td>
                <td class="text-end fw-bold">{{ $fmt($r['v']) }}</td>
                <td class="text-end"><a href="{{ route('pickups.create', ['location_id' => $r['location']->id]) }}" class="btn btn-sm btn-success">Jadwalkan Angkut</a></td>
            </tr>
        @endforeach
        </tbody>
    </table></div></div>

    <h5>Langkah 1: Kriteria dan Bobot</h5>
    <div class="card mb-4"><div class="table-responsive"><table class="table table-sm mb-0">
        <thead><tr><th>Kode</th><th>Kriteria</th><th>Tipe</th><th class="text-end">Bobot (w)</th></tr></thead>
        <tbody>
        @foreach($cr as $c)<tr><td>{{ $c->kode }}</td><td>{{ $c->nama }}</td><td>{{ ucfirst($c->tipe) }}</td><td class="text-end">{{ $fmt($c->bobot, 2) }}</td></tr>@endforeach
        <tr class="table-light"><td colspan="3" class="text-end"><strong>Total</strong></td><td class="text-end"><strong>{{ $fmt($hasil['totalBobot'], 2) }}</strong></td></tr>
        </tbody>
    </table></div></div>

    <h5>Langkah 2: Matriks Keputusan (X)</h5>
    <div class="card mb-4"><div class="table-responsive"><table class="table table-sm mb-0">
        <thead><tr><th>Lokasi</th>@foreach($cr as $c)<th class="text-end">{{ $c->kode }}</th>@endforeach</tr></thead>
        <tbody>
        @foreach($hasil['rows'] as $r)
            <tr><td>{{ $r['location']->nama_lokasi }}</td>@foreach($cr as $c)<td class="text-end">{{ $fmt($r['x'][$c->id], 2) }}</td>@endforeach</tr>
        @endforeach
        </tbody>
    </table></div></div>

    <h5>Langkah 3: Normalisasi (R)</h5>
    <p class="text-muted small mb-2">Benefit: r = x / max(x). Cost: r = min(x) / x.</p>
    <div class="card mb-4"><div class="table-responsive"><table class="table table-sm mb-0">
        <thead><tr><th>Lokasi</th>@foreach($cr as $c)<th class="text-end">{{ $c->kode }} <small class="text-muted">({{ $c->tipe }})</small></th>@endforeach</tr></thead>
        <tbody>
        @foreach($hasil['rows'] as $r)
            <tr><td>{{ $r['location']->nama_lokasi }}</td>@foreach($cr as $c)<td class="text-end">{{ $fmt($r['r'][$c->id]) }}</td>@endforeach</tr>
        @endforeach
        </tbody>
    </table></div></div>

    <h5>Langkah 4: Nilai Preferensi (V = &Sigma; w &times; r)</h5>
    <div class="card mb-4"><div class="table-responsive"><table class="table table-sm mb-0">
        <thead><tr><th>Lokasi</th><th>Perhitungan</th><th class="text-end">V</th><th class="text-end">Rank</th></tr></thead>
        <tbody>
        @foreach($hasil['rows'] as $r)
            <tr>
                <td>{{ $r['location']->nama_lokasi }}</td>
                <td class="small text-muted">@foreach($cr as $c)({{ $fmt($c->bobot, 2) }} &times; {{ $fmt($r['r'][$c->id], 3) }}){{ $loop->last ? '' : ' + ' }}@endforeach</td>
                <td class="text-end fw-bold">{{ $fmt($r['v']) }}</td><td class="text-end">{{ $r['rank'] }}</td>
            </tr>
        @endforeach
        </tbody>
    </table></div></div>
@endif

@if($riwayat->isNotEmpty())
    <h5>Riwayat Hasil Tersimpan</h5>
    <div class="card"><div class="table-responsive"><table class="table table-sm mb-0">
        <thead><tr><th>Periode</th><th>Peringkat</th><th>Lokasi</th><th class="text-end">Nilai V</th></tr></thead>
        <tbody>@foreach($riwayat as $s)<tr><td>{{ $s->periode->format('d/m/Y') }}</td><td>{{ $s->peringkat }}</td><td>{{ $s->location->nama_lokasi }}</td><td class="text-end">{{ $fmt($s->nilai_preferensi) }}</td></tr>@endforeach</tbody>
    </table></div></div>
@endif
@endsection
