<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MountainBooking extends Model
{
    protected $fillable = ['user_id', 'mountain_id', 'hike_date', 'return_date', 'team_size', 'members', 'status', 'qr_code', 'checkin_time', 'checkout_time', 'total_duration_minutes'];

    protected $casts = [
        'members' => 'array',
        'hike_date' => 'date',
        'return_date' => 'date',
        'checkin_time' => 'datetime',
        'checkout_time' => 'datetime',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function mountain() {
        return $this->belongsTo(Mountain::class);
    }

    public function equipmentRentals() {
        return $this->hasMany(MountainEquipmentRental::class, 'booking_id');
    }

    public function feedbacks() {
        return $this->hasMany(MountainFeedback::class, 'booking_id');
    }

    public function hikerStatuses() {
        return $this->hasMany(MountainHikerStatus::class, 'booking_id');
    }
}
