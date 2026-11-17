<?php

namespace Tests\Unit;

use App\Services\SawService;
use PHPUnit\Framework\TestCase;

class SawServiceTest extends TestCase
{
    public function test_perhitungan_saw_sesuai_hitungan_manual(): void
    {
        $kriteria = [
            1 => ['bobot' => 0.35, 'tipe' => 'benefit'],
            2 => ['bobot' => 0.15, 'tipe' => 'cost'],
            3 => ['bobot' => 0.30, 'tipe' => 'benefit'],
            4 => ['bobot' => 0.20, 'tipe' => 'benefit'],
        ];
        $matrix = [
            'A' => [1 => 500, 2 => 10, 3 => 5, 4 => 3000],
            'B' => [1 => 300, 2 => 5, 3 => 3, 4 => 5000],
            'C' => [1 => 400, 2 => 8, 3 => 7, 4 => 2000],
        ];

        $hasil = SawService::proses($kriteria, $matrix);

        $this->assertSame(['A', 'C', 'B'], array_keys($hasil));
        $this->assertEqualsWithDelta(0.7593, $hasil['A']['v'], 0.0001);
        $this->assertEqualsWithDelta(0.7538, $hasil['C']['v'], 0.0001);
        $this->assertEqualsWithDelta(0.6886, $hasil['B']['v'], 0.0001);
        $this->assertSame(1, $hasil['A']['rank']);
        $this->assertSame(3, $hasil['B']['rank']);
    }
}
