@extends('layouts.app')
@section('title', $item->exists ? 'Edit Kriteria' : 'Tambah Kriteria')
@section('content')
<h4 class="mb-3">{{ $item->exists ? 'Edit' : 'Tambah' }} Kriteria</h4>
<div class="card"><div class="card-body">
<form method="POST" action="{{ $item->exists ? route('criterias.update', $item) : route('criterias.store') }}">
    @csrf @if($item->exists) @method('PUT') @endif
    <div class="row">
        <div class="col-md-3 mb-3"><label class="form-label">Kode</label><input name="kode" class="form-control" value="{{ old('kode', $item->kode) }}" placeholder="C5" required></div>
        <div class="col-md-9 mb-3"><label class="form-label">Nama Kriteria</label><input name="nama" class="form-control" value="{{ old('nama', $item->nama) }}" required></div>
    </div>
    <div class="row">
        <div class="col-md-5 mb-3"><label class="form-label">Sumber Data</label>
            <select name="sumber" class="form-select" required>
                @foreach(\App\Models\Criteria::SUMBER as $k => $label)
                    <option value="{{ $k }}" @selected(old('sumber', $item->sumber) === $k)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4 mb-3"><label class="form-label">Tipe</label>
            <select name="tipe" class="form-select" required>
                <option value="benefit" @selected(old('tipe', $item->tipe) === 'benefit')>Benefit (makin besar makin baik)</option>
                <option value="cost" @selected(old('tipe', $item->tipe) === 'cost')>Cost (makin kecil makin baik)</option>
            </select>
        </div>
        <div class="col-md-3 mb-3"><label class="form-label">Bobot (0-1)</label><input type="number" step="0.01" min="0.01" max="1" name="bobot" class="form-control" value="{{ old('bobot', $item->bobot) }}" required></div>
    </div>
    <div class="form-text mb-3">Total seluruh bobot kriteria harus 1 agar perhitungan SAW dapat dijalankan.</div>
    <button class="btn btn-success">Simpan</button> <a href="{{ route('criterias.index') }}" class="btn btn-secondary">Batal</a>
</form></div></div>
@endsection
