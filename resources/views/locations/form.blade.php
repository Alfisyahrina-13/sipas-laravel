@extends('layouts.app')
@section('title', $item->exists ? 'Edit Lokasi' : 'Tambah Lokasi')
@section('content')
<h4 class="mb-3">{{ $item->exists ? 'Edit' : 'Tambah' }} Lokasi</h4>
<div class="card"><div class="card-body">
<form method="POST" action="{{ $item->exists ? route('locations.update', $item) : route('locations.store') }}">
    @csrf @if($item->exists) @method('PUT') @endif
    <div class="mb-3"><label class="form-label">Nama Lokasi</label><input name="nama_lokasi" class="form-control" value="{{ old('nama_lokasi', $item->nama_lokasi) }}" required></div>
    <div class="mb-3"><label class="form-label">Alamat</label><input name="alamat" class="form-control" value="{{ old('alamat', $item->alamat) }}"></div>
    <div class="row">
        <div class="col-md-6 mb-3"><label class="form-label">Jarak ke TPA (km)</label><input type="number" step="0.01" min="0" name="jarak_tpa" class="form-control" value="{{ old('jarak_tpa', $item->jarak_tpa) }}" required></div>
        <div class="col-md-6 mb-3"><label class="form-label">Kepadatan Penduduk Sekitar (jiwa)</label><input type="number" min="0" name="kepadatan_penduduk" class="form-control" value="{{ old('kepadatan_penduduk', $item->kepadatan_penduduk) }}" required></div>
    </div>
    <button class="btn btn-success">Simpan</button> <a href="{{ route('locations.index') }}" class="btn btn-secondary">Batal</a>
</form></div></div>
@endsection
