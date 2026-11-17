<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class VehicleController extends Controller
{
    private function rules(?Vehicle $vehicle = null): array
    {
        return [
            'nama_armada' => 'required|string|max:255',
            'plat_nomor' => ['required', 'string', 'max:20', Rule::unique('vehicles', 'plat_nomor')->ignore($vehicle?->id)],
            'kapasitas' => 'required|integer|min:1',
        ];
    }

    public function index()
    {
        return view('vehicles.index', ['items' => Vehicle::latest()->paginate(10)]);
    }

    public function create()
    {
        return view('vehicles.form', ['item' => new Vehicle]);
    }

    public function store(Request $request)
    {
        Vehicle::create($request->validate($this->rules()));

        return redirect()->route('vehicles.index')->with('success', 'Armada berhasil ditambahkan.');
    }

    public function edit(Vehicle $vehicle)
    {
        return view('vehicles.form', ['item' => $vehicle]);
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        $vehicle->update($request->validate($this->rules($vehicle)));

        return redirect()->route('vehicles.index')->with('success', 'Armada berhasil diperbarui.');
    }

    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();

        return redirect()->route('vehicles.index')->with('success', 'Armada berhasil dihapus.');
    }
}
