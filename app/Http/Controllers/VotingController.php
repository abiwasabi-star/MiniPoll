<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Inertia\Inertia;

use App\Models\VotingSetting;
use App\Models\Skater;
use App\Models\Vote;
use Illuminate\Http\Request;

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

     public function store(Request $request)
    {
        //validates the submitted contestant ID.
        $validated = $request->validate([
            'skater_id' => ['required', 'integer', 'exists:skaters,id'],
        ]);

        //retrieves the selected contestant.
        $skater = Skater::findOrFail($validated['skater_id']);

        //retrieves the common voting settings.
        $settings = VotingSetting::first();

        //checks whether both voting dates have been configured.
        if (
            !$settings ||
            !$settings->voting_starts_at ||
            !$settings->voting_ends_at
           ) {
            return back()->withErrors([
            'vote' => 'Voting has not been configured yet.',
           ]);
           }

        //checks whether voting has started.
        if (now()->lt($settings->voting_starts_at)) {
        return back()->withErrors([
        'vote' => 'Voting has not started yet.',
        ]);
        }

        //checks whether voting has closed.
        if (now()->gt($settings->voting_ends_at)) {
        return back()->withErrors([
        'vote' => 'Voting has closed.',
        ]);
         }

        //gets the currently authenticated user.
        $user = $request->user();

        //checks whether this user has already voted in this category.
        if ($user->votes()->where('category_id', $skater->category_id)->exists()) {
            return back()->withErrors([
                'vote' => 'You have already voted in this category.',
            ]);
        }

        //saves the vote.
        Vote::create([
            'user_id' => $user->id,
            'category_id' => $skater->category_id,
            'skater_id' => $skater->id,
        ]);

        return back()->with('success', "You have successfully voted for {$skater->name} in {$skater->category->name}!");
    }

    public function show(Skater $skater)
{
    //gets the category the contestant belongs to
     $skater->load('category');

    //gets other contestants in the same category.
    $otherSkaters = Skater::where('category_id', $skater->category_id)
        ->where('id', '!=', $skater->id)
        ->get(['id', 'name']);

    //retrieves the common voting settings.
    $settings = VotingSetting::first();

    //determines the current voting status.
    if (
        !$settings ||
        !$settings->voting_starts_at ||
        !$settings->voting_ends_at
    ) {
        $votingStatus = 'not_configured';
    } elseif (now()->lt($settings->voting_starts_at)) {
        $votingStatus = 'upcoming';
    } elseif (now()->gt($settings->voting_ends_at)) {
        $votingStatus = 'closed';
    } else {
        $votingStatus = 'open';
    }

    //checks whether the logged-in user has voted in this category.
    $hasVoted = request()->user()
        ->votes()
        ->where('category_id', $skater->category_id)
        ->exists();

    return Inertia::render('Voting/Show', [
        'skater' => $skater,
        'otherSkaters' => $otherSkaters,
        'votingStatus' => $votingStatus,
        'hasVoted' => $hasVoted,
    ]);
}
}