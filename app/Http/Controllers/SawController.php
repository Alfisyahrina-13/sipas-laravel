<?php

namespace App\Http\Controllers;

use App\Models\SawResult;
use App\Services\SawService;

class SawController extends Controller
{
    public function index(SawService $saw)
    {
        return view('saw.index', ['hasil' => $saw->hitung(), 'riwayat' => SawResult::with('location')->latest('periode')->latest('id')->take(10)->get()]);
    }

    public function simpan(SawService $saw)
    {
        $hasil = $saw->hitung();
        if (! $hasil['valid'] || empty($hasil['rows'])) {
            return back()->with('error', 'Tidak ada hasil yang bisa disimpan. Periksa bobot dan data sampah.');
        }
        $saw->simpan($hasil['rows']);

        return back()->with('success', 'Hasil perangkingan hari ini berhasil disimpan.');
    }
}
