<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $guarded = [];

    public function pickups()
    {
        return $this->hasMany(Pickup::class);
    }
}
