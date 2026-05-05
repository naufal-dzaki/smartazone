<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MountainSosSignal extends Model
{
    protected $fillable = ['device_id', 'timestamp', 'latitude', 'longitude'];

    protected $casts = [
        'timestamp' => 'datetime',
    ];

    public function device() {
        return $this->belongsTo(MountainDevice::class, 'device_id');
    }
}
