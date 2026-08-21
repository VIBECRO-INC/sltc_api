<?php

namespace App\Models\Concerns;

use Illuminate\Support\Str;

/**
 * Génère automatiquement un slug unique à la création si le champ
 * "slug" est vide. La source du texte est définie par la propriété
 * $slugSource sur le modèle (défaut: "name").
 */
trait HasSlug
{
    public static function bootHasSlug(): void
    {
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $source = $model->slugSource ?? 'name';
                $base = Str::slug($model->{$source} ?? Str::random(8));
                $slug = $base;
                $i = 1;
                while (static::where('slug', $slug)->exists()) {
                    $slug = "{$base}-{$i}";
                    $i++;
                }
                $model->slug = $slug;
            }
        });
    }
}
