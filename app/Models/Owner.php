<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Owner extends Model
{
    protected $fillable = [
        'login',
        'type',
        'avatar_url',
    ];

    public function repositories(): BelongsToMany
    {
        return $this->belongsToMany(Repository::class);
    }
}
