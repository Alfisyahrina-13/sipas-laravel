<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Pickup;
use App\Models\Vehicle;
use App\Models\WasteRecord;
use App\Services\SawService;

class DashboardController extends Controller
{
    public function index(SawService $saw)
    {
        $hasil = $saw->hitung();

        return view('dashboard', [
            'jumlahLokasi' => Location::count(),
            'jumlahArmada' => Vehicle::count(),
            'jumlahData' => WasteRecord::count(),
            'jadwal' => Pickup::where('status', '!=', 'Selesai')->count(),
            'top' => array_slice($hasil['rows'], 0, 5),
            'terbaru' => Pickup::with(['location', 'vehicle'])->latest()->take(5)->get(),
        ]);
    }
}
