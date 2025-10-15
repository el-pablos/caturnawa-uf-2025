<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Models\CompetitionTimeline;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CompetitionTimelineController extends Controller
{
    /**
     * Display a listing of competition timelines
     */
    public function index(Request $request)
    {
        $query = CompetitionTimeline::with('competition');

        // Filter by competition if provided
        if ($request->has('competition_id') && $request->competition_id) {
            $query->where('competition_id', $request->competition_id);
        }

        $timelines = $query->orderBy('order', 'asc')->paginate(20);
        $competitions = Competition::active()->get();

        return view('admin.competition-timelines.index', compact('timelines', 'competitions'));
    }

    /**
     * Show the form for creating a new timeline
     */
    public function create()
    {
        $competitions = Competition::active()->get();
        return view('admin.competition-timelines.create', compact('competitions'));
    }

    /**
     * Store a newly created timeline in storage
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'competition_id' => 'required|exists:competitions,id',
            'month' => 'required|string|max:20',
            'day' => 'required|integer|min:1|max:31',
            'year' => 'required|integer|min:2024|max:2030',
            'title' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'title_id' => 'nullable|string|max:255',
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        CompetitionTimeline::create([
            'competition_id' => $request->competition_id,
            'month' => $request->month,
            'day' => $request->day,
            'year' => $request->year,
            'title' => $request->title,
            'title_en' => $request->title_en,
            'title_id' => $request->title_id,
            'order' => $request->order,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return redirect()->route('admin.competition-timelines.index')
            ->with('success', 'Competition timeline created successfully.');
    }

    /**
     * Show the form for editing the specified timeline
     */
    public function edit(CompetitionTimeline $competitionTimeline)
    {
        $competitions = Competition::active()->get();
        return view('admin.competition-timelines.edit', compact('competitionTimeline', 'competitions'));
    }

    /**
     * Update the specified timeline in storage
     */
    public function update(Request $request, CompetitionTimeline $competitionTimeline)
    {
        $validator = Validator::make($request->all(), [
            'competition_id' => 'required|exists:competitions,id',
            'month' => 'required|string|max:20',
            'day' => 'required|integer|min:1|max:31',
            'year' => 'required|integer|min:2024|max:2030',
            'title' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'title_id' => 'nullable|string|max:255',
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $competitionTimeline->update([
            'competition_id' => $request->competition_id,
            'month' => $request->month,
            'day' => $request->day,
            'year' => $request->year,
            'title' => $request->title,
            'title_en' => $request->title_en,
            'title_id' => $request->title_id,
            'order' => $request->order,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return redirect()->route('admin.competition-timelines.index')
            ->with('success', 'Competition timeline updated successfully.');
    }

    /**
     * Remove the specified timeline from storage
     */
    public function destroy(CompetitionTimeline $competitionTimeline)
    {
        $competitionTimeline->delete();

        return redirect()->route('admin.competition-timelines.index')
            ->with('success', 'Competition timeline deleted successfully.');
    }
}

