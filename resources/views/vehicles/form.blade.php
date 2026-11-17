@extends('layouts.app')
@section('title', $item->exists ? 'Edit Armada' : 'Tambah Armada')
@section('content')
<h4 class="mb-3">{{ $item->exists ? 'Edit' : 'Tambah' }} Armada</h4>
<div class="card"><div class="card-body">
<form method="POST" action="{{ $item->exists ? route('vehicles.update', $item) : route('vehicles.store') }}">
    @csrf @if($item->exists) @method('PUT') @endif
    <div class="mb-3"><label class="form-label">Nama Armada</label><input name="nama_armada" class="form-control" value="{{ old('nama_armada', $item->nama_armada) }}" required></div>
    <div class="row">
        <div class="col-md-6 mb-3"><label class="form-label">Plat Nomor</label><input name="plat_nomor" class="form-control" value="{{ old('plat_nomor', $item->plat_nomor) }}" required></div>
        <div class="col-md-6 mb-3"><label class="form-label">Kapasitas (kg)</label><input type="number" min="1" name="kapasitas" class="form-control" value="{{ old('kapasitas', $item->kapasitas) }}" required></div>
    </div>
    <button class="btn btn-success">Simpan</button> <a href="{{ route('vehicles.index') }}" class="btn btn-secondary">Batal</a>
</form></div></div>
@endsection
