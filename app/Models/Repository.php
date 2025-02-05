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
        'approved_at'
    ];

    protected $casts = [
        'private' => 'boolean',
        'fork' => 'boolean',
        'archived' => 'boolean',
        'disabled' => 'boolean',
        'topics' => 'array',
        'approved_at' => 'datetime'
    ];

    public function owners(): BelongsToMany
    {
        return $this->belongsToMany(Owner::class);
    }

    public function licenses(): BelongsToMany
    {
        return $this->belongsToMany(License::class);
    }

    public function scopeApproved($query)
    {
        return $query->where('approved_at', '>', now());
    }
}
