<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    private function rules(): array
    {
        return [
            'nama_lokasi' => 'required|string|max:255',
            'alamat' => 'nullable|string|max:255',
            'jarak_tpa' => 'required|numeric|min:0',
            'kepadatan_penduduk' => 'required|integer|min:0',
        ];
    }

    public function index()
    {
        return view('locations.index', ['items' => Location::latest()->paginate(10)]);
    }

    public function create()
    {
        return view('locations.form', ['item' => new Location]);
    }

    public function store(Request $request)
    {
        Location::create($request->validate($this->rules()));

        return redirect()->route('locations.index')->with('success', 'Lokasi berhasil ditambahkan.');
    }

    public function edit(Location $location)
    {
        return view('locations.form', ['item' => $location]);
    }

    public function update(Request $request, Location $location)
    {
        $location->update($request->validate($this->rules()));

        return redirect()->route('locations.index')->with('success', 'Lokasi berhasil diperbarui.');
    }

    public function destroy(Location $location)
    {
        $location->delete();

        return redirect()->route('locations.index')->with('success', 'Lokasi berhasil dihapus.');
    }
}
