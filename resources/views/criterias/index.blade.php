@extends('layouts.app')
@section('title', 'Kriteria')
@section('content')
<div class="d-flex justify-content-between mb-3"><h4>Kriteria dan Bobot SAW</h4><a href="{{ route('criterias.create') }}" class="btn btn-success"><i class="bi bi-plus-lg"></i> Tambah</a></div>
@if(abs($total - 1) < 0.0001)
    <div class="alert alert-success py-2">Total bobot = {{ $total }} (valid).</div>
@else
    <div class="alert alert-warning py-2"><strong>Total bobot = {{ $total }}.</strong> Perhitungan SAW baru berjalan jika total bobot tepat 1.</div>
@endif
<div class="card"><div class="table-responsive">
<table class="table table-hover mb-0">
    <thead><tr><th>Kode</th><th>Nama Kriteria</th><th>Sumber Data</th><th>Tipe</th><th class="text-end">Bobot</th><th></th></tr></thead>
    <tbody>
    @forelse($items as $i)
        <tr>
            <td><strong>{{ $i->kode }}</strong></td><td>{{ $i->nama }}</td><td>{{ \App\Models\Criteria::SUMBER[$i->sumber] ?? $i->sumber }}</td>
            <td><span class="badge bg-{{ $i->tipe === 'benefit' ? 'success' : 'danger' }}">{{ ucfirst($i->tipe) }}</span></td>
            <td class="text-end">{{ number_format($i->bobot, 2, ',', '.') }}</td>
            <td class="text-end text-nowrap">
                <a href="{{ route('criterias.edit', $i) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                <form method="POST" action="{{ route('criterias.destroy', $i) }}" class="d-inline" onsubmit="return confirm('Hapus kriteria ini?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Hapus</button></form>
            </td>
        </tr>
    @empty
        <tr><td colspan="6" class="text-center text-muted py-4">Belum ada data.</td></tr>
    @endforelse
    </tbody>
</table></div></div>
<p class="text-muted small mt-2">Benefit: semakin besar semakin diprioritaskan. Cost: semakin kecil semakin diprioritaskan.</p>
@endsection
