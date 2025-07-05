<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use App\Models\Competition;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AchievementController extends Controller
{
    /**
     * Get UNAS Fest 2024 achievements
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        // Cache for 1 hour since this is precomputed data
        $achievements = Cache::remember('achievements_data', 3600, function () {
            return $this->getAchievementsData();
        });

        return response()->json([
            'success' => true,
            'data' => $achievements,
            'computed_at' => now()->toISOString(),
        ]);
    }

    /**
     * Get achievements data from database or compute if not available
     */
    private function getAchievementsData()
    {
        $achievements = [];

        // Total Competitions
        $totalCompetitions = Achievement::getByKey('total_competitions');
        if ($totalCompetitions) {
            $achievements['total_competitions'] = $totalCompetitions->data;
        } else {
            $achievements['total_competitions'] = $this->computeTotalCompetitions();
        }

        // Participants per Competition
        $participantsPerCompetition = Achievement::getByKey('participants_per_competition');
        if ($participantsPerCompetition) {
            $achievements['participants_per_competition'] = $participantsPerCompetition->data;
        } else {
            $achievements['participants_per_competition'] = $this->computeParticipantsPerCompetition();
        }

        // Universities List
        $universitiesList = Achievement::getByKey('universities_list');
        if ($universitiesList) {
            $achievements['universities_list'] = $universitiesList->data;
        } else {
            $achievements['universities_list'] = $this->computeUniversitiesList();
        }

        return $achievements;
    }

    /**
     * Compute total competitions
     */
    private function computeTotalCompetitions()
    {
        $total = Competition::count();
        $active = Competition::where('is_active', true)->count();

        $data = [
            'total' => $total,
            'active' => $active,
            'completed' => $total - $active,
        ];

        Achievement::updateData(
            'total_competitions',
            'Total Kompetisi',
            $data,
            'Jumlah total kompetisi yang telah diselenggarakan'
        );

        return $data;
    }

    /**
     * Compute participants per competition
     */
    private function computeParticipantsPerCompetition()
    {
        $competitions = Competition::withCount(['registrations' => function ($query) {
            $query->where('status', 'confirmed');
        }])->get();

        $data = $competitions->map(function ($competition) {
            return [
                'id' => $competition->id,
                'name' => $competition->name,
                'category' => $competition->category,
                'participants_count' => $competition->registrations_count,
                'registration_start' => $competition->registration_start,
                'registration_end' => $competition->registration_end,
            ];
        })->toArray();

        Achievement::updateData(
            'participants_per_competition',
            'Peserta per Kompetisi',
            $data,
            'Jumlah peserta yang terdaftar di setiap kompetisi'
        );

        return $data;
    }

    /**
     * Compute universities list
     */
    private function computeUniversitiesList()
    {
        $universities = Registration::where('status', 'confirmed')
            ->whereNotNull('institution')
            ->select('institution')
            ->selectRaw('COUNT(*) as participants_count')
            ->groupBy('institution')
            ->orderBy('participants_count', 'desc')
            ->get();

        $data = $universities->map(function ($university) {
            return [
                'name' => $university->institution,
                'participants_count' => $university->participants_count,
            ];
        })->toArray();

        Achievement::updateData(
            'universities_list',
            'Daftar Universitas',
            $data,
            'Daftar universitas yang berpartisipasi beserta jumlah peserta'
        );

        return $data;
    }
}
