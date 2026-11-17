@extends('layouts.app')
@section('title', 'Data Sampah')
@section('content')
<div class="d-flex justify-content-between mb-3"><h4>Data Kondisi Sampah</h4><a href="{{ route('records.create') }}" class="btn btn-success"><i class="bi bi-plus-lg"></i> Input Data</a></div>
<div class="card"><div class="table-responsive">
<table class="table table-hover mb-0">
    <thead><tr><th>#</th><th>Tanggal Input</th><th>Lokasi</th><th class="text-end">Volume (kg)</th><th>Terakhir Diangkut</th><th>Petugas</th><th></th></tr></thead>
    <tbody>
    @forelse($items as $i)
        <tr>
            <td>{{ $items->firstItem() + $loop->index }}</td><td>{{ $i->tanggal_input->format('d/m/Y') }}</td><td>{{ $i->location->nama_lokasi }}</td>
            <td class="text-end">{{ number_format($i->volume_kg, 2, ',', '.') }}</td><td>{{ $i->terakhir_diangkut->format('d/m/Y') }}</td><td>{{ $i->user->name ?? '-' }}</td>
            <td class="text-end text-nowrap">
                <a href="{{ route('records.edit', $i) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                <form method="POST" action="{{ route('records.destroy', $i) }}" class="d-inline" onsubmit="return confirm('Hapus data ini?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Hapus</button></form>
            </td>
        </tr>
    @empty
        <tr><td colspan="7" class="text-center text-muted py-4">Belum ada data.</td></tr>
    @endforelse
    </tbody>
</table></div></div>
<div class="mt-3">{{ $items->links('pagination::bootstrap-5') }}</div>
<p class="text-muted small mt-2">Perhitungan SAW memakai data <strong>terbaru</strong> dari tiap lokasi.</p>
@endsection
