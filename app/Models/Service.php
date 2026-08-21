<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasSlug;

    protected $fillable = [
        'name', 'slug', 'short_description', 'description', 'image', 'features',
        'cta_label', 'cta_url', 'sort_order', 'is_published',
        'seo_title', 'seo_description',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'features' => 'array',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}