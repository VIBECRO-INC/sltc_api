<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeoSetting extends Model
{
    protected $fillable = [
        'page_slug', 'title', 'meta_description', 'canonical_url',
        'og_title', 'og_description', 'og_image', 'robots', 'schema_json',
    ];

    protected $casts = [
        'schema_json' => 'array',
    ];
}