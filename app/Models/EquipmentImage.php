<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EquipmentImage extends Model
{
    protected $fillable = ['equipment_id', 'path', 'order'];

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }
}
