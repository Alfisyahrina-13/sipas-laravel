<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $guarded = [];

    public function records()
    {
        return $this->hasMany(WasteRecord::class);
    }

    public function latestRecord()
    {
        return $this->hasOne(WasteRecord::class)->latestOfMany(['tanggal_input' => 'max', 'id' => 'max']);
    }

    public function pickups()
    {
        return $this->hasMany(Pickup::class);
    }
}
