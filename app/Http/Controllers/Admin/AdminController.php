<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Skater;
use Inertia\Inertia;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    
public function index()
{
    $categories = Category::withCount('skaters')
        ->with([
            'skaters' => function ($query) {
                $query->withCount('votes');
            }
        ])
        ->orderByRaw("
            CASE slug
                WHEN 'best-female-skater' THEN 1
                WHEN 'best-male-skater' THEN 2
                ELSE 3
            END
        ")
        ->get();

    return Inertia::render('Admin/Dashboard', [
        'totalSkaters' => Skater::count(),
        'totalCategories' => Category::count(),
        'categories' => $categories,
    ]);
}

public function store(Request $request)
{
    // Validates the submitted form data
    $validated = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'category_id' => ['required', 'exists:categories,id'],
        'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
    ]);

    //stores the uploaded photo
    if ($request->hasFile('photo')) {
        $validated['photo'] = $request->file('photo')
            ->store('skaters', 'public');
    }

    //save contestant in db
    Skater::create($validated);

    //returns to dashboard with a success message
    return redirect()->route('admin.dashboard')
        ->with('success', 'Contestant added successfully!');
}

public function update(Request $request, Skater $skater)
{
    $validated = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'category_id' => ['required', 'exists:categories,id'],
        'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
    ]);

    // If a new photo has been uploaded
    $oldPhoto = $skater->photo;

    if ($request->hasFile('photo')) {
        $validated['photo'] = $request->file('photo')
        ->store('skaters', 'public');

        //delete the previous photo
        if ($skater->photo) {
            Storage::disk('public')->delete($skater->photo);
        }

        //store the new photo
        $validated['photo'] = $request->file('photo')
            ->store('skaters', 'public');
    }

    //update the contestant
    $skater->update($validated);

    if (isset($validated['photo']) && $oldPhoto) {
        Storage::disk('public')->delete($oldPhoto);
    }

    return redirect()->route('admin.dashboard')
        ->with('success', 'Contestant updated successfully!');
}

public function destroy(Skater $skater)
{
    //first check whether the contestant has votes
    if ($skater->votes()->exists()) {
        return redirect()->route('admin.dashboard')
            ->with('error', 'This contestant cannot be deleted because they have received votes.');
    }

    // Delete the contestant's photo from storage
    if ($skater->photo) {
        Storage::disk('public')->delete($skater->photo);
    }

    // Delete the contestant from the database
    $skater->delete();

    return redirect()->route('admin.dashboard')
        ->with('success', 'Contestant deleted successfully!');
}

public function contestants()
{
    $categories = Category::with([
        'skaters' => function ($query) {
            $query->withCount('votes');
        }
    ])->get();

    return Inertia::render('Admin/Contestants', [
        'categories' => $categories,
    ]);
}

public function categories() //retrieves all cates frm db n skaters n renders
{
    $categories = Category::withCount('skaters')->get();

    return Inertia::render('Admin/Categories', [
        'categories' => $categories,
    ]);
}

public function storeCategory(Request $request)
{
    $validated = $request->validate([
        'name' => ['required', 'string', 'max:255'],
    ]);

    $slug = Str::slug($validated['name']);

    //checks whether the generated slug already exists
    if (Category::where('slug', $slug)->exists()) {
        return back()->withErrors([
            'name' => 'A category with this name already exists.',
        ])->withInput();
    }

    Category::create([
        'name' => $validated['name'],
        'slug' => $slug,
    ]);

    return redirect()->route('admin.categories')
        ->with('success', 'Category added successfully!');
}

public function updateCategory(Request $request, Category $category)
{
    $validated = $request->validate([
        'name' => ['required', 'string', 'max:255'],
    ]);

    $slug = Str::slug($validated['name']);

    //checking for duplicate slugs, excluding this category
    if (
        Category::where('slug', $slug)
            ->where('id', '!=', $category->id)
            ->exists()
    ) {
        return back()->withErrors([
            'name' => 'A category with this name already exists.',
        ])->withInput();
    }

    $category->update([
        'name' => $validated['name'],
        'slug' => $slug,
    ]);

    return redirect()->route('admin.categories')
        ->with('success', 'Category updated successfully!');
}

public function destroyCategory(Category $category)
{
    //prevents deleting categories that have contestants
    if ($category->skaters()->exists()) {
        return back()->with('error',
            'This category cannot be deleted because it has contestants.'
        );
    }

    $category->delete();

    return redirect()->route('admin.categories')
        ->with('success', 'Category deleted successfully!');
}



}