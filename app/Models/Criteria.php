<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Criteria extends Model
{
    protected $table = 'criterias';
    protected $guarded = [];

    /** Sumber data yang tersedia untuk sebuah kriteria. */
    public const SUMBER = [
        'volume_kg'          => 'Volume sampah (kg)',
        'jarak_tpa'          => 'Jarak ke TPA (km)',
        'hari_sejak_angkut'  => 'Hari sejak terakhir diangkut',
        'kepadatan_penduduk' => 'Kepadatan penduduk sekitar',
    ];
}
