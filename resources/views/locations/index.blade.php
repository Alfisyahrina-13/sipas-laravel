@extends('layouts.app')
@section('title', 'Lokasi')
@section('content')
<div class="d-flex justify-content-between mb-3"><h4>Data Lokasi / TPS</h4><a href="{{ route('locations.create') }}" class="btn btn-success"><i class="bi bi-plus-lg"></i> Tambah</a></div>
<div class="card"><div class="table-responsive">
<table class="table table-hover mb-0">
    <thead><tr><th>#</th><th>Nama Lokasi</th><th>Alamat</th><th class="text-end">Jarak TPA (km)</th><th class="text-end">Kepadatan (jiwa)</th><th></th></tr></thead>
    <tbody>
    @forelse($items as $i)
        <tr>
            <td>{{ $items->firstItem() + $loop->index }}</td><td>{{ $i->nama_lokasi }}</td><td>{{ $i->alamat }}</td>
            <td class="text-end">{{ number_format($i->jarak_tpa, 2, ',', '.') }}</td>
            <td class="text-end">{{ number_format($i->kepadatan_penduduk, 0, ',', '.') }}</td>
            <td class="text-end text-nowrap">
                <a href="{{ route('locations.edit', $i) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                <form method="POST" action="{{ route('locations.destroy', $i) }}" class="d-inline" onsubmit="return confirm('Hapus lokasi ini beserta datanya?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Hapus</button></form>
            </td>
        </tr>
    @empty
        <tr><td colspan="6" class="text-center text-muted py-4">Belum ada data.</td></tr>
    @endforelse
    </tbody>
</table></div></div>
<div class="mt-3">{{ $items->links('pagination::bootstrap-5') }}</div>
@endsection
