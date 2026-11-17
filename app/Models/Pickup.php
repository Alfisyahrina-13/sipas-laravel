<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pickup extends Model
{
    protected $guarded = [];

    protected $casts = ['tanggal_angkut' => 'date'];

    public const STATUS = ['Dijadwalkan', 'Dalam Proses', 'Selesai'];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
