<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MountainEquipment extends Model
{
    protected $fillable = ['mountain_id', 'name', 'description', 'quantity', 'price', 'image_url'];

    public function mountain() {
        return $this->belongsTo(Mountain::class);
    }
}
