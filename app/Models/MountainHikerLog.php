<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MountainHikerLog extends Model
{
    protected $fillable = ['device_id', 'heart_rate', 'spo2', 'stress_level', 'latitude', 'longitude', 'timestamp'];

    protected $casts = [
        'timestamp' => 'datetime',
    ];

    public function device() {
        return $this->belongsTo(MountainDevice::class, 'device_id');
    }
}
