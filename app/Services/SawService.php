<?php

namespace App\Services;

use App\Models\Criteria;
use App\Models\Location;
use App\Models\Pickup;
use App\Models\SawResult;
use Illuminate\Support\Facades\DB;

/**
 * Implementasi metode Simple Additive Weighting (SAW).
 *
 * 1. Matriks keputusan X (alternatif x kriteria)
 * 2. Normalisasi: benefit r = x / max(x); cost r = min(x) / x
 * 3. Nilai preferensi V = sum(w * r)
 * 4. Peringkat: V terbesar = prioritas pengangkutan tertinggi
 */
class SawService
{
    /**
     * Fungsi murni (tanpa database) agar mudah diuji.
     *
     * @param array $kriteria [id => ['bobot' => float, 'tipe' => 'benefit'|'cost']]
     * @param array $matrix   [altId => [kriteriaId => nilai]]
     * @return array [altId => ['x'=>[], 'r'=>[], 'v'=>float, 'rank'=>int]] terurut dari peringkat 1
     */
    public static function proses(array $kriteria, array $matrix): array
    {
        $max = $min = [];
        foreach ($kriteria as $cid => $k) {
            $kolom = array_column($matrix, $cid);
            $max[$cid] = $kolom ? max($kolom) : 0;
            $min[$cid] = $kolom ? min($kolom) : 0;
        }

        $hasil = [];
        foreach ($matrix as $aid => $nilai) {
            $r = [];
            $v = 0.0;
            foreach ($kriteria as $cid => $k) {
                $x = (float) $nilai[$cid];
                if ($k['tipe'] === 'benefit') {
                    $r[$cid] = $max[$cid] > 0 ? $x / $max[$cid] : 0.0;
                } else {
                    $r[$cid] = $x > 0 ? $min[$cid] / $x : 1.0;
                }
                $v += $k['bobot'] * $r[$cid];
            }
            $hasil[$aid] = ['x' => $nilai, 'r' => $r, 'v' => $v, 'rank' => 0];
        }

        uasort($hasil, fn ($a, $b) => $b['v'] <=> $a['v']);

        $rank = 1;
        foreach ($hasil as &$h) {
            $h['rank'] = $rank++;
        }

        return $hasil;
    }

    /** Ambil data dari database lalu hitung. */
    public function hitung(): array
    {
        $criterias = Criteria::orderBy('kode')->get();
        $totalBobot = round((float) $criterias->sum('bobot'), 4);

        $out = [
            'criterias' => $criterias,
            'totalBobot' => $totalBobot,
            'valid' => $criterias->count() > 0 && abs($totalBobot - 1) < 0.0001,
            'rows' => [],
        ];

        $locations = Location::with('latestRecord')->get()->filter(fn ($l) => $l->latestRecord);
        if (! $out['valid'] || $locations->isEmpty()) {
            return $out;
        }

        $kriteria = $matrix = [];
        foreach ($criterias as $c) {
            $kriteria[$c->id] = ['bobot' => (float) $c->bobot, 'tipe' => $c->tipe];
        }

        foreach ($locations as $loc) {
            foreach ($criterias as $c) {
                $matrix[$loc->id][$c->id] = $this->nilai($loc, $c->sumber);
            }
        }

        $hasil = self::proses($kriteria, $matrix);

        foreach ($hasil as $lid => $h) {
            $out['rows'][] = ['location' => $locations->firstWhere('id', $lid)] + $h;
        }

        return $out;
    }

    /** Simpan hasil perangkingan (satu set per hari). */
    public function simpan(array $rows): void
    {
        DB::transaction(function () use ($rows) {
            $periode = today()->toDateString();
            SawResult::where('periode', $periode)->delete();
            foreach ($rows as $row) {
                SawResult::create([
                    'location_id' => $row['location']->id,
                    'nilai_preferensi' => round($row['v'], 4),
                    'peringkat' => $row['rank'],
                    'periode' => $periode,
                ]);
            }
        });
    }

    private function nilai(Location $loc, string $sumber): float
    {
        $rec = $loc->latestRecord;

        return match ($sumber) {
            'volume_kg' => (float) $rec->volume_kg,
            'jarak_tpa' => (float) $loc->jarak_tpa,
            'kepadatan_penduduk' => (float) $loc->kepadatan_penduduk,
            'hari_sejak_angkut' => (float) $this->hariSejakAngkut($loc),
            default => 0.0,
        };
    }

    /** Pakai tanggal terakhir diangkut dari catatan atau pengangkutan "Selesai" yang lebih baru. */
    private function hariSejakAngkut(Location $loc): int
    {
        $terakhir = $loc->latestRecord->terakhir_diangkut;
        $selesai = Pickup::where('location_id', $loc->id)->where('status', 'Selesai')->max('tanggal_angkut');
        if ($selesai && $selesai > $terakhir->toDateString()) {
            $terakhir = \Carbon\Carbon::parse($selesai);
        }

        return max(0, (int) $terakhir->startOfDay()->diffInDays(today(), false));
    }
}
