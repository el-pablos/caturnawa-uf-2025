<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use Illuminate\Http\Request;

class CompetitionController extends Controller
{
    public function index(Request $request)
    {
        $query = Competition::query();

        // Filter by category
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Search by name or description
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $competitions = $query->orderBy('name')
            ->get()
            ->map(function($competition) {
                return [
                    'id' => $competition->id,
                    'name' => $competition->name,
                    'category' => $competition->category,
                    'status' => $competition->status,
                    'is_team' => $competition->is_team,
                    'max_participants' => $competition->max_participants,
                    'registration_start' => $competition->registration_start?->toISOString(),
                    'registration_end' => $competition->registration_end?->toISOString(),
                    'price' => $competition->price,
                    'early_bird_price' => $competition->early_bird_price,
                    'early_bird_end' => $competition->early_bird_end?->toISOString(),
                    'registrations_count' => $competition->registrations()->count(),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $competitions
        ]);
    }

    public function show(Competition $competition)
    {
        $data = [
            'id' => $competition->id,
            'name' => $competition->name,
            'description' => $competition->description,
            'category' => $competition->category,
            'status' => $competition->status,
            'is_team' => $competition->is_team,
            'max_participants' => $competition->max_participants,
            'registration_start' => $competition->registration_start?->toISOString(),
            'registration_end' => $competition->registration_end?->toISOString(),
            'competition_start' => $competition->competition_start?->toISOString(),
            'competition_end' => $competition->competition_end?->toISOString(),
            'price' => $competition->price,
            'early_bird_price' => $competition->early_bird_price,
            'early_bird_end' => $competition->early_bird_end?->toISOString(),
            'rules' => $competition->rules,
            'prizes' => $competition->prizes,
            'requirements' => $competition->requirements,
            'contact_person' => $competition->contact_person,
            'created_at' => $competition->created_at->toISOString(),
            'updated_at' => $competition->updated_at->toISOString(),
            'registrations_count' => $competition->registrations()->count(),
            'confirmed_registrations_count' => $competition->registrations()->where('status', 'confirmed')->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
}
