<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MountainEquipmentRental extends Model
{
    protected $fillable = ['booking_id', 'mountain_id', 'equipment_id', 'quantity', 'status'];

    public function booking() {
        return $this->belongsTo(MountainBooking::class, 'booking_id');
    }

    public function equipment() {
        return $this->belongsTo(MountainEquipment::class, 'equipment_id');
    }
}
