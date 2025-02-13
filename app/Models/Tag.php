<?php

namespace App\Models;

use Spatie\Tags\Tag as TagsTag;

class Tag extends TagsTag
{
    protected $fillable = [
        'name',
        'slug',
        'type',
        'order_column',
        'is_featured'
    ];

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}
