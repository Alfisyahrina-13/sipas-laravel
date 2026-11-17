@extends('layouts.app')
@section('title', $item->exists ? 'Edit Data Sampah' : 'Input Data Sampah')
@section('content')
<h4 class="mb-3">{{ $item->exists ? 'Edit' : 'Input' }} Data Sampah</h4>
<div class="card"><div class="card-body">
<form method="POST" action="{{ $item->exists ? route('records.update', $item) : route('records.store') }}">
    @csrf @if($item->exists) @method('PUT') @endif
    <div class="mb-3"><label class="form-label">Lokasi</label>
        <select name="location_id" class="form-select" required>
            <option value="">-- pilih lokasi --</option>
            @foreach($locations as $l)<option value="{{ $l->id }}" @selected(old('location_id', $item->location_id) == $l->id)>{{ $l->nama_lokasi }}</option>@endforeach
        </select>
    </div>
    <div class="row">
        <div class="col-md-4 mb-3"><label class="form-label">Volume Sampah (kg)</label><input type="number" step="0.01" min="0" name="volume_kg" class="form-control" value="{{ old('volume_kg', $item->volume_kg) }}" required></div>
        <div class="col-md-4 mb-3"><label class="form-label">Terakhir Diangkut</label><input type="date" name="terakhir_diangkut" class="form-control" value="{{ old('terakhir_diangkut', optional($item->terakhir_diangkut)->format('Y-m-d')) }}" max="{{ today()->format('Y-m-d') }}" required></div>
        <div class="col-md-4 mb-3"><label class="form-label">Tanggal Input</label><input type="date" name="tanggal_input" class="form-control" value="{{ old('tanggal_input', optional($item->tanggal_input)->format('Y-m-d') ?? today()->format('Y-m-d')) }}" required></div>
    </div>
    <div class="mb-3"><label class="form-label">Catatan</label><input name="catatan" class="form-control" value="{{ old('catatan', $item->catatan) }}"></div>
    <button class="btn btn-success">Simpan</button> <a href="{{ route('records.index') }}" class="btn btn-secondary">Batal</a>
</form></div></div>
@endsection
