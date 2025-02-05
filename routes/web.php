<?php

use Inertia\Inertia;
use App\Services\GithubApiService;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;

Route::get('/', function () {
    return inertia('Index');
})->name('index');
