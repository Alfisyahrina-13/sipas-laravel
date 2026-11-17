<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\WasteRecord;
use Illuminate\Http\Request;

class WasteRecordController extends Controller
{
    private function rules(): array
    {
        return [
            'location_id' => 'required|exists:locations,id',
            'volume_kg' => 'required|numeric|min:0',
            'terakhir_diangkut' => 'required|date|before_or_equal:today',
            'tanggal_input' => 'required|date',
            'catatan' => 'nullable|string|max:255',
        ];
    }

    public function index()
    {
        return view('records.index', ['items' => WasteRecord::with(['location', 'user'])->latest('tanggal_input')->latest('id')->paginate(10)]);
    }

    public function create()
    {
        return view('records.form', ['item' => new WasteRecord, 'locations' => Location::orderBy('nama_lokasi')->get()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules());
        $data['user_id'] = $request->user()->id;
        WasteRecord::create($data);

        return redirect()->route('records.index')->with('success', 'Data sampah berhasil ditambahkan.');
    }

    public function edit(WasteRecord $record)
    {
        return view('records.form', ['item' => $record, 'locations' => Location::orderBy('nama_lokasi')->get()]);
    }

    public function update(Request $request, WasteRecord $record)
    {
        $record->update($request->validate($this->rules()));

        return redirect()->route('records.index')->with('success', 'Data sampah berhasil diperbarui.');
    }

    public function destroy(WasteRecord $record)
    {
        $record->delete();

        return redirect()->route('records.index')->with('success', 'Data sampah berhasil dihapus.');
    }
}
