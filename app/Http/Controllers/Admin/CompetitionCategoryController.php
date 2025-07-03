<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompetitionCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CompetitionCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = CompetitionCategory::ordered()->paginate(20);

        return view('admin.competition-categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.competition-categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
            'color' => 'nullable|string|size:7|regex:/^#[0-9A-Fa-f]{6}$/',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);

        // Check if slug already exists
        $originalSlug = $data['slug'];
        $counter = 1;
        while (CompetitionCategory::where('slug', $data['slug'])->exists()) {
            $data['slug'] = $originalSlug . '-' . $counter;
            $counter++;
        }

        CompetitionCategory::create($data);

        return redirect()->route('admin.competition-categories.index')
            ->with('success', 'Kategori kompetisi berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(CompetitionCategory $competitionCategory)
    {
        $competitions = $competitionCategory->competitions()->paginate(10);

        return view('admin.competition-categories.show', compact('competitionCategory', 'competitions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CompetitionCategory $competitionCategory)
    {
        return view('admin.competition-categories.edit', compact('competitionCategory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CompetitionCategory $competitionCategory)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
            'color' => 'nullable|string|size:7|regex:/^#[0-9A-Fa-f]{6}$/',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $data = $request->all();

        // Update slug if name changed
        if ($request->name !== $competitionCategory->name) {
            $data['slug'] = Str::slug($request->name);

            // Check if slug already exists (excluding current record)
            $originalSlug = $data['slug'];
            $counter = 1;
            while (CompetitionCategory::where('slug', $data['slug'])
                    ->where('id', '!=', $competitionCategory->id)->exists()) {
                $data['slug'] = $originalSlug . '-' . $counter;
                $counter++;
            }
        }

        $competitionCategory->update($data);

        return redirect()->route('admin.competition-categories.index')
            ->with('success', 'Kategori kompetisi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CompetitionCategory $competitionCategory)
    {
        // Check if category has competitions
        if ($competitionCategory->competitions()->count() > 0) {
            return back()->with('error', 'Kategori tidak dapat dihapus karena masih memiliki kompetisi.');
        }

        $competitionCategory->delete();

        return redirect()->route('admin.competition-categories.index')
            ->with('success', 'Kategori kompetisi berhasil dihapus.');
    }
}
