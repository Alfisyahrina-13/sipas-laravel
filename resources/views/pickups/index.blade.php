@extends('layouts.app')
@section('title', 'Penugasan')
@section('content')
<div class="d-flex justify-content-between mb-3"><h4>Penugasan Pengangkutan</h4><a href="{{ route('pickups.create') }}" class="btn btn-success"><i class="bi bi-plus-lg"></i> Buat Penugasan</a></div>
<div class="card"><div class="table-responsive">
<table class="table table-hover mb-0">
    <thead><tr><th>#</th><th>Tanggal</th><th>Lokasi</th><th>Armada</th><th>Status</th><th>Dibuat oleh</th><th></th></tr></thead>
    <tbody>
    @forelse($items as $i)
        <tr>
            <td>{{ $items->firstItem() + $loop->index }}</td><td>{{ $i->tanggal_angkut->format('d/m/Y') }}</td><td>{{ $i->location->nama_lokasi }}</td>
            <td>{{ $i->vehicle->nama_armada }} <small class="text-muted">({{ $i->vehicle->plat_nomor }})</small></td>
            <td><span class="badge bg-{{ $i->status === 'Selesai' ? 'success' : ($i->status === 'Dalam Proses' ? 'warning' : 'secondary') }}">{{ $i->status }}</span></td>
            <td>{{ $i->user->name ?? '-' }}</td>
            <td class="text-end text-nowrap">
                <a href="{{ route('pickups.edit', $i) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                <form method="POST" action="{{ route('pickups.destroy', $i) }}" class="d-inline" onsubmit="return confirm('Hapus penugasan ini?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Hapus</button></form>
            </td>
        </tr>
    @empty
        <tr><td colspan="7" class="text-center text-muted py-4">Belum ada penugasan.</td></tr>
    @endforelse
    </tbody>
</table></div></div>
<div class="mt-3">{{ $items->links('pagination::bootstrap-5') }}</div>
<p class="text-muted small mt-2">Penugasan berstatus <strong>Selesai</strong> akan mereset hitungan "hari sejak terakhir diangkut" pada perhitungan SAW.</p>
@endsection
