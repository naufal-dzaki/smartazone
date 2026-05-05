<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MountainHikerStatus extends Model
{
    protected $fillable = ['booking_id', 'mountain_id', 'device_id', 'hiker_name', 'hiker_nik', 'hiker_phone', 'status', 'started_at', 'ended_at'];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    public function booking() {
        return $this->belongsTo(MountainBooking::class, 'booking_id');
    }

    public function device() {
        return $this->belongsTo(MountainDevice::class, 'device_id');
    }
}
