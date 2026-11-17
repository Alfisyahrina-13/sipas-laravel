@extends('layouts.app')
@section('title', 'Armada')
@section('content')
<div class="d-flex justify-content-between mb-3"><h4>Data Armada Pengangkut</h4><a href="{{ route('vehicles.create') }}" class="btn btn-success"><i class="bi bi-plus-lg"></i> Tambah</a></div>
<div class="card"><div class="table-responsive">
<table class="table table-hover mb-0">
    <thead><tr><th>#</th><th>Nama Armada</th><th>Plat Nomor</th><th class="text-end">Kapasitas (kg)</th><th></th></tr></thead>
    <tbody>
    @forelse($items as $i)
        <tr>
            <td>{{ $items->firstItem() + $loop->index }}</td><td>{{ $i->nama_armada }}</td><td>{{ $i->plat_nomor }}</td>
            <td class="text-end">{{ number_format($i->kapasitas, 0, ',', '.') }}</td>
            <td class="text-end text-nowrap">
                <a href="{{ route('vehicles.edit', $i) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                <form method="POST" action="{{ route('vehicles.destroy', $i) }}" class="d-inline" onsubmit="return confirm('Hapus armada ini?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Hapus</button></form>
            </td>
        </tr>
    @empty
        <tr><td colspan="5" class="text-center text-muted py-4">Belum ada data.</td></tr>
    @endforelse
    </tbody>
</table></div></div>
<div class="mt-3">{{ $items->links('pagination::bootstrap-5') }}</div>
@endsection
