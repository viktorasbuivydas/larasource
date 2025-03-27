<?php

use Illuminate\Foundation\Application;

use Illuminate\Console\Scheduling\Schedule;
use App\Console\Commands\CrawlGithubCommand;
use App\Http\Middleware\HandleInertiaRequests;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Console\Commands\ApproveInterestingRepositories;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
        ]);

        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->withCommands([
        CrawlGithubCommand::class
    ])
    ->withSchedule(function (Schedule $schedule) {
        $schedule->command(ApproveInterestingRepositories::class)->at('10:10');
    })
    ->create();
