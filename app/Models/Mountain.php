<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mountain extends Model
{
    protected $fillable = ['name', 'subdomains', 'location', 'description', 'banner_image_url', 'content', 'gallery', 'faq', 'meta', 'status'];

    protected $casts = [
        'gallery' => 'array',
        'faq' => 'array',
        'meta' => 'array',
    ];

    public function users() {
        return $this->hasMany(User::class);
    }

    public function bookings() {
        return $this->hasMany(MountainBooking::class);
    }

    public function devices() {
        return $this->hasMany(MountainDevice::class);
    }

    public function equipments() {
        return $this->hasMany(MountainEquipment::class);
    }
}
