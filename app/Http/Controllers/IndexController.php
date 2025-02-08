<?php

namespace App\Http\Controllers;

use App\Models\Repository;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;

class IndexController extends Controller
{
    public function index()
    {
        $repositories = Cache::remember('repositories-' . request()->query('page', 1), 60, function () {
            return Repository::query()
                ->approved()
                ->with(['owners', 'licenses'])
                ->paginate(20);
        });

        return inertia('Index', [
            'repositories' => $repositories
        ]);
    }
}
