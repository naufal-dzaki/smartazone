<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MountainDevice extends Model
{
    protected $fillable = ['mountain_id', 'battery_level'];

    public function mountain() {
        return $this->belongsTo(Mountain::class);
    }

    public function hikerLogs() {
        return $this->hasMany(MountainHikerLog::class, 'device_id');
    }

    public function sosSignals() {
        return $this->hasMany(MountainSosSignal::class, 'device_id');
    }
}
