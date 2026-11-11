<?php

namespace Database\Seeders;

use App\Models\Criteria;
use App\Models\Location;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\WasteRecord;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create(['name' => 'Admin SIPAS', 'email' => 'admin@sipas.test', 'password' => 'password', 'role' => 'admin']);
        $petugas = User::create(['name' => 'Petugas Lapangan', 'email' => 'petugas@sipas.test', 'password' => 'password', 'role' => 'petugas']);

        $kriteria = [
            ['C1', 'Volume sampah (kg)', 'benefit', 'volume_kg', 0.35],
            ['C2', 'Jarak ke TPA (km)', 'cost', 'jarak_tpa', 0.15],
            ['C3', 'Hari sejak terakhir diangkut', 'benefit', 'hari_sejak_angkut', 0.30],
            ['C4', 'Kepadatan penduduk sekitar', 'benefit', 'kepadatan_penduduk', 0.20],
        ];
        foreach ($kriteria as [$kode, $nama, $tipe, $sumber, $bobot]) {
            Criteria::create(compact('kode', 'nama', 'tipe', 'sumber', 'bobot'));
        }

        // Data contoh sama dengan contoh hitung di README (hasil: A=0,7593; C=0,7538; B=0,6886)
        $data = [
            ['TPS Lampineung', 'Jl. Teuku Umar, Banda Aceh', 10, 3000, 500, 5],
            ['TPS Peunayong', 'Jl. Panglima Polem, Banda Aceh', 5, 5000, 300, 3],
            ['TPS Ulee Kareng', 'Jl. Ulee Kareng, Banda Aceh', 8, 2000, 400, 7],
        ];
        foreach ($data as [$nama, $alamat, $jarak, $penduduk, $volume, $hari]) {
            $loc = Location::create([
                'nama_lokasi' => $nama, 'alamat' => $alamat,
                'jarak_tpa' => $jarak, 'kepadatan_penduduk' => $penduduk,
            ]);
            WasteRecord::create([
                'location_id' => $loc->id, 'user_id' => $petugas->id,
                'volume_kg' => $volume,
                'terakhir_diangkut' => today()->subDays($hari),
                'tanggal_input' => today(),
                'catatan' => 'Data awal (seeder)',
            ]);
        }

        Vehicle::create(['nama_armada' => 'Dump Truck 01', 'plat_nomor' => 'BL 1001 AA', 'kapasitas' => 5000]);
        Vehicle::create(['nama_armada' => 'Pick Up Sampah 02', 'plat_nomor' => 'BL 2002 AB', 'kapasitas' => 1500]);
    }
}
