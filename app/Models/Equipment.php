<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    use HasSlug;

    protected $fillable = [
        'name', 'slug', 'category', 'brand', 'model', 'year', 'description', 'image',
        'specifications', 'capacity', 'power', 'dimensions', 'weight',
        'applications', 'availability', 'status', 'location',
        'seo_title', 'seo_description', 'is_published',
    ];

    protected $casts = [
        'specifications' => 'array',
        'applications' => 'array',
        'is_published' => 'boolean',
    ];

    public function photos()
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}