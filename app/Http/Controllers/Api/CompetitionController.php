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
                    'is_team' => $competition->is_team ?? false,
                    'max_participants' => $competition->max_participants ?? 0,
                    'registration_start' => $competition->registration_start?->toISOString(),
                    'registration_end' => $competition->registration_end?->toISOString(),
                    'price' => (float) $competition->price,
                    'early_bird_price' => (float) ($competition->early_bird_price ?? 0),
                    'early_bird_end' => $competition->early_bird_deadline?->toISOString(),
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
            'is_team' => $competition->is_team ?? false,
            'max_participants' => $competition->max_participants ?? 0,
            'registration_start' => $competition->registration_start?->toISOString(),
            'registration_end' => $competition->registration_end?->toISOString(),
            'registration_deadline' => $competition->registration_end?->toISOString(),
            'round1_date' => $competition->competition_start?->toISOString(),
            'semifinal_date' => $competition->competition_start?->addDays(5)->toISOString(),
            'final_date' => $competition->competition_end?->toISOString(),
            'competition_start' => $competition->competition_start?->toISOString(),
            'competition_end' => $competition->competition_end?->toISOString(),
            'price' => (float) $competition->price,
            'early_bird_price' => (float) ($competition->early_bird_price ?? 0),
            'early_bird_end' => $competition->early_bird_deadline?->toISOString(),
            'rules' => $competition->rules ?? [],
            'prizes' => $competition->prizes ?? [],
            'requirements' => $competition->requirements ?? [],
            'contact_person' => $competition->contact_person,
            'created_at' => $competition->created_at->toISOString(),
            'updated_at' => $competition->updated_at->toISOString(),
            'registrations_count' => $competition->registrations()->count(),
            'confirmed_registrations_count' => $competition->registrations()->where('status', 'confirmed')->count(),
            'days_left' => $competition->days_left ?? 0,
            'timeline' => $competition->timeline ?? [],
        ];

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Get competition description by section
     *
     * @param Competition $competition
     * @param string $section
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDescription(Competition $competition, $section = 'main')
    {
        $descriptions = \App\Models\CompetitionDescription::getByCompetitionAndSection(
            $competition->id,
            $section
        );

        $formattedDescriptions = $descriptions->map(function ($description) {
            return [
                'id' => $description->id,
                'title' => $description->title,
                'content' => $description->content,
                'order' => $description->order,
                'created_at' => $description->created_at->toISOString(),
                'updated_at' => $description->updated_at->toISOString(),
                'created_by' => $description->creator ? [
                    'id' => $description->creator->id,
                    'name' => $description->creator->name,
                ] : null,
                'updated_by' => $description->updater ? [
                    'id' => $description->updater->id,
                    'name' => $description->updater->name,
                ] : null,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'competition_id' => $competition->id,
                'competition_name' => $competition->name,
                'section' => $section,
                'descriptions' => $formattedDescriptions,
                'available_sections' => \App\Models\CompetitionDescription::getSectionsByCompetition($competition->id),
            ]
        ]);
    }

    /**
     * Get competition statistics
     *
     * @param Competition $competition
     * @return \Illuminate\Http\JsonResponse
     */
    public function statistics(Competition $competition)
    {
        try {
            $stats = [
                'total_registrations' => $competition->registrations()->count(),
                'confirmed_registrations' => $competition->registrations()->where('status', 'confirmed')->count(),
                'pending_registrations' => $competition->registrations()->where('status', 'pending')->count(),
                'total_submissions' => $competition->registrations()
                    ->whereHas('submissions', function($q) {
                        $q->where('is_final', true);
                    })->count(),
                'total_scores' => $competition->scores()->where('is_final', true)->count(),
            ];

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch statistics',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get competition participants
     *
     * @param Competition $competition
     * @return \Illuminate\Http\JsonResponse
     */
    public function participants(Competition $competition)
    {
        try {
            $participants = $competition->registrations()
                ->where('status', 'confirmed')
                ->with(['user', 'teamMembers'])
                ->get()
                ->map(function ($registration) {
                    return [
                        'id' => $registration->id,
                        'team_name' => $registration->team_name,
                        'user' => [
                            'id' => $registration->user->id,
                            'name' => $registration->user->name,
                            'email' => $registration->user->email,
                        ],
                        'team_members' => $registration->teamMembers->map(function ($member) {
                            return [
                                'name' => $member->name,
                                'email' => $member->email,
                                'role' => $member->role ?? 'member',
                            ];
                        }),
                        'status' => $registration->status,
                        'registered_at' => $registration->created_at->toISOString(),
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $participants
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch participants',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
