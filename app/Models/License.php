<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class License extends Model
{
    protected $fillable = [
        'name',
        'url'
    ];

    public function repositories(): BelongsToMany
    {
        return $this->belongsToMany(Repository::class);
    }
}
