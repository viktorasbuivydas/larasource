<?php

namespace App\Http\Controllers;

use Spatie\Tags\Tag;
use App\Models\Repository;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class IndexController extends Controller
{
    public function index()
    {
        $page = request()->query('page', 1);

        $tags = Tag::limit(20)->get();
        // if ($page === 1) {
        //     $repositories = Cache::remember('repositories-' . request()->query('page', 1), 60, function () {
        //         return $this->getRepositories();
        //     });

        //     return inertia('Index', [
        //         'repositories' => $repositories,
        //         'tags' => $tags
        //     ]);
        // }

        if (request()->wantsJson()) {
            return $this->getRepositories();
        }

        return inertia('Index', [
            'repositories' => $this->getRepositories(),
            'tags' => $tags,
        ]);
    }

    private function getRepositories()
    {
        return QueryBuilder::for(Repository::class)
            ->with('owners')
            ->allowedFilters([
                AllowedFilter::belongsTo('type'),
                //tags
                AllowedFilter::scope('tag'),
                AllowedFilter::callback('stars_between', function ($query, $value) {
                    $query->whereBetween('stargazers_count', $value);
                }),
                AllowedFilter::callback('watchers_between', function ($query, $value) {
                    $query->whereBetween('watchers_count', $value);
                }),
                AllowedFilter::callback('forks_between', function ($query, $value) {
                    $query->whereBetween('forks_count', $value);
                }),
            ])
            ->approved()
            ->paginate(16)
            ->withQueryString();
    }
}
