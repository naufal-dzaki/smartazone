<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MountainFeedback extends Model
{
    protected $fillable = ['booking_id', 'mountain_id', 'image_url', 'rating', 'comment'];

    public function booking() {
        return $this->belongsTo(MountainBooking::class, 'booking_id');
    }

    public function mountain() {
         return $this->belongsTo(Mountain::class);
    }
}
