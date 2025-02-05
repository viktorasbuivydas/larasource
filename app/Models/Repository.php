<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Repository extends Model
{
    protected $fillable = [
        'github_id',
        'name',
        'full_name',
        'private',
        'html_url',
        'description',
        'fork',
        'language',
        'forks_count',
        'stargazers_count',
        'watchers_count',
        'archived',
        'disabled',
        'open_issues_count',
        'visibility',
        'topics',
        'created_repository_at',
        'updated_repository_at',
    ];

    protected $casts = [
        'private' => 'boolean',
        'fork' => 'boolean',
        'archived' => 'boolean',
        'disabled' => 'boolean',
        'topics' => 'array',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(Owner::class);
    }

    public function license(): BelongsTo
    {
        return $this->belongsTo(License::class);
    }

    public function owners(): BelongsToMany
    {
        return $this->belongsToMany(Owner::class);
    }

    public function licenses(): BelongsToMany
    {
        return $this->belongsToMany(License::class);
    }
}
