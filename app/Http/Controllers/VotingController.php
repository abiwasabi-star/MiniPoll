<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Inertia\Inertia;

class VotingController extends Controller
{
    public function index()
    {
        $categories = Category::with('skaters')->orderByRaw("
        CASE slug
            WHEN 'best-female-skater' THEN 1
            WHEN 'best-male-skater' THEN 2
            ELSE 3
        END")
        ->get();

        return Inertia::render('Voting/Index', [
            'categories' => $categories,
        ]);
    }
}