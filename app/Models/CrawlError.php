<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CrawlError extends Model
{
    protected $fillable = [
        'star_range',
        'page',
        'error_message',
        'stack_trace'
    ];
}
