<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SawResult extends Model
{
    protected $guarded = [];

    protected $casts = ['periode' => 'date'];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
