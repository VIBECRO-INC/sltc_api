<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Model;

class EquipmentCategory extends Model
{
    use HasSlug;

    protected $fillable = ['name', 'slug', 'order'];

    public function equipments()
    {
        return $this->hasMany(Equipment::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
