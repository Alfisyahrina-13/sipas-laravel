<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Pickup;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PickupController extends Controller
{
    private function rules(): array
    {
        return [
            'location_id' => 'required|exists:locations,id',
            'vehicle_id' => 'required|exists:vehicles,id',
            'tanggal_angkut' => 'required|date',
            'status' => ['required', Rule::in(Pickup::STATUS)],
            'catatan' => 'nullable|string|max:255',
        ];
    }

    private function options(): array
    {
        return ['locations' => Location::orderBy('nama_lokasi')->get(), 'vehicles' => Vehicle::orderBy('nama_armada')->get()];
    }

    public function index()
    {
        return view('pickups.index', ['items' => Pickup::with(['location', 'vehicle', 'user'])->latest('tanggal_angkut')->latest('id')->paginate(10)]);
    }

    public function create(Request $request)
    {
        $item = new Pickup(['tanggal_angkut' => today(), 'status' => 'Dijadwalkan', 'location_id' => $request->query('location_id')]);

        return view('pickups.form', ['item' => $item] + $this->options());
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules());
        $data['user_id'] = $request->user()->id;
        Pickup::create($data);

        return redirect()->route('pickups.index')->with('success', 'Penugasan pengangkutan berhasil dibuat.');
    }

    public function edit(Pickup $pickup)
    {
        return view('pickups.form', ['item' => $pickup] + $this->options());
    }

    public function update(Request $request, Pickup $pickup)
    {
        $pickup->update($request->validate($this->rules()));

        return redirect()->route('pickups.index')->with('success', 'Penugasan berhasil diperbarui.');
    }

    public function destroy(Pickup $pickup)
    {
        $pickup->delete();

        return redirect()->route('pickups.index')->with('success', 'Penugasan berhasil dihapus.');
    }
}
