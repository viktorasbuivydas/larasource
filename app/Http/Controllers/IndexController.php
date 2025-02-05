<?php

namespace App\Http\Controllers;

use App\Models\Repository;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index()
    {
        $repositories = Repository::query()
            ->with(['owners', 'licenses'])
            ->paginate(20);

        return inertia('Index', [
            'repositories' => $repositories
        ]);
    }
}
