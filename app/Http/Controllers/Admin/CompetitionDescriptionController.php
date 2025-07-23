<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Models\CompetitionDescription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CompetitionDescriptionController extends Controller
{
    /**
     * Display descriptions for a competition
     */
    public function index(Competition $competition)
    {
        $descriptions = $competition->descriptions()
            ->with(['creator', 'updater'])
            ->orderBy('section')
            ->orderBy('order')
            ->get()
            ->groupBy('section');

        $sections = CompetitionDescription::getSectionsByCompetition($competition->id);

        // Get or create Terms & Conditions
        $termsAndConditions = $competition->descriptions()
            ->where('section', 'terms')
            ->where('title', 'Syarat & Ketentuan')
            ->first();

        return view('admin.competitions.descriptions.index', compact('competition', 'descriptions', 'sections', 'termsAndConditions'));
    }

    /**
     * Show form for creating new description
     */
    public function create(Competition $competition)
    {
        $sections = [
            'main' => 'Deskripsi Utama',
            'rules' => 'Peraturan',
            'prizes' => 'Hadiah',
            'requirements' => 'Persyaratan',
            'timeline' => 'Timeline',
            'faq' => 'FAQ',
        ];

        return view('admin.competitions.descriptions.create', compact('competition', 'sections'));
    }

    /**
     * Store new description
     */
    public function store(Request $request, Competition $competition)
    {
        $validator = Validator::make($request->all(), [
            'section' => 'required|string|max:50',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'order' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        CompetitionDescription::create([
            'competition_id' => $competition->id,
            'section' => $request->section,
            'title' => $request->title,
            'content' => $request->content,
            'order' => $request->order ?? 0,
            'is_active' => true,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);

        return redirect()
            ->route('admin.competitions.descriptions.index', $competition)
            ->with('success', 'Deskripsi berhasil ditambahkan');
    }

    /**
     * Show form for editing description
     */
    public function edit(Competition $competition, CompetitionDescription $description)
    {
        $sections = [
            'main' => 'Deskripsi Utama',
            'rules' => 'Peraturan',
            'prizes' => 'Hadiah',
            'requirements' => 'Persyaratan',
            'timeline' => 'Timeline',
            'faq' => 'FAQ',
        ];

        return view('admin.competitions.descriptions.edit', compact('competition', 'description', 'sections'));
    }

    /**
     * Update description
     */
    public function update(Request $request, Competition $competition, CompetitionDescription $description)
    {
        $validator = Validator::make($request->all(), [
            'section' => 'required|string|max:50',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $description->update([
            'section' => $request->section,
            'title' => $request->title,
            'content' => $request->content,
            'order' => $request->order ?? 0,
            'is_active' => $request->has('is_active'),
            'updated_by' => Auth::id(),
        ]);

        return redirect()
            ->route('admin.competitions.descriptions.index', $competition)
            ->with('success', 'Deskripsi berhasil diperbarui');
    }

    /**
     * Update Terms & Conditions
     */
    public function updateTerms(Request $request, Competition $competition)
    {
        $validator = Validator::make($request->all(), [
            'terms_content' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Find or create Terms & Conditions
        $termsAndConditions = $competition->descriptions()
            ->where('section', 'terms')
            ->where('title', 'Syarat & Ketentuan')
            ->first();

        if (!$termsAndConditions) {
            $termsAndConditions = new CompetitionDescription();
            $termsAndConditions->competition_id = $competition->id;
            $termsAndConditions->section = 'terms';
            $termsAndConditions->title = 'Syarat & Ketentuan';
            $termsAndConditions->order = 1;
            $termsAndConditions->is_active = true;
            $termsAndConditions->created_by = Auth::id();
        }

        $termsAndConditions->content = $request->terms_content;
        $termsAndConditions->updated_by = Auth::id();
        $termsAndConditions->save();

        return redirect()
            ->route('admin.competitions.descriptions.index', $competition)
            ->with('success', 'Syarat & Ketentuan berhasil diperbarui');
    }

    /**
     * Delete description
     */
    public function destroy(Competition $competition, CompetitionDescription $description)
    {
        $description->delete();

        return redirect()
            ->route('admin.competitions.descriptions.index', $competition)
            ->with('success', 'Deskripsi berhasil dihapus');
    }
}
