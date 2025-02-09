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
        $page = request()->query('page', 1);

        if ($page === 1) {
            $repositories = Cache::remember('repositories-' . request()->query('page', 1), 60, function () {
                return $this->getRepositories();
            });

            return inertia('Index', [
                'repositories' => $repositories
            ]);
        }

        return inertia('Index', [
            'repositories' => $this->getRepositories()
        ]);
    }

    private function getRepositories()
    {
        return Repository::query()
            ->approved()
            ->with(['owners', 'licenses'])
            ->paginate(20);
    }
}
