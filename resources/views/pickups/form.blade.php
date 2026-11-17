@extends('layouts.app')
@section('title', $item->exists ? 'Edit Penugasan' : 'Buat Penugasan')
@section('content')
<h4 class="mb-3">{{ $item->exists ? 'Edit' : 'Buat' }} Penugasan</h4>
<div class="card"><div class="card-body">
<form method="POST" action="{{ $item->exists ? route('pickups.update', $item) : route('pickups.store') }}">
    @csrf @if($item->exists) @method('PUT') @endif
    <div class="row">
        <div class="col-md-6 mb-3"><label class="form-label">Lokasi</label>
            <select name="location_id" class="form-select" required>
                <option value="">-- pilih lokasi --</option>
                @foreach($locations as $l)<option value="{{ $l->id }}" @selected(old('location_id', $item->location_id) == $l->id)>{{ $l->nama_lokasi }}</option>@endforeach
            </select>
        </div>
        <div class="col-md-6 mb-3"><label class="form-label">Armada</label>
            <select name="vehicle_id" class="form-select" required>
                <option value="">-- pilih armada --</option>
                @foreach($vehicles as $v)<option value="{{ $v->id }}" @selected(old('vehicle_id', $item->vehicle_id) == $v->id)>{{ $v->nama_armada }} ({{ $v->plat_nomor }})</option>@endforeach
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3"><label class="form-label">Tanggal Pengangkutan</label><input type="date" name="tanggal_angkut" class="form-control" value="{{ old('tanggal_angkut', optional($item->tanggal_angkut)->format('Y-m-d')) }}" required></div>
        <div class="col-md-6 mb-3"><label class="form-label">Status</label>
            <select name="status" class="form-select" required>
                @foreach(\App\Models\Pickup::STATUS as $s)<option @selected(old('status', $item->status) === $s)>{{ $s }}</option>@endforeach
            </select>
        </div>
    </div>
    <div class="mb-3"><label class="form-label">Catatan</label><input name="catatan" class="form-control" value="{{ old('catatan', $item->catatan) }}"></div>
    <button class="btn btn-success">Simpan</button> <a href="{{ route('pickups.index') }}" class="btn btn-secondary">Batal</a>
</form></div></div>
@endsection
