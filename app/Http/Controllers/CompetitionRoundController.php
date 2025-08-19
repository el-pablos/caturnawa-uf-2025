<?php

namespace App\Http\Controllers;

use App\Models\Competition;
use App\Models\CompetitionRound;
use App\Models\Registration;
use App\Models\Score;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Controller untuk mengelola babak kompetisi dan hasil
 * Mengimplementasikan sistem round selection dan results display
 */
class CompetitionRoundController extends Controller
{
    /**
     * Menampilkan halaman pemilihan babak kompetisi
     * Similar to caturnawa-24.tams.my.id/matalomba/shortmovie
     */
    public function index(Competition $competition)
    {
        // Load rounds dengan relasi yang diperlukan
        $rounds = $competition->rounds()
            ->active()
            ->orderBy('round_number')
            ->with(['matches'])
            ->get();

        // Jika tidak ada rounds, buat default rounds
        if ($rounds->isEmpty()) {
            $this->createDefaultRounds($competition);
            $rounds = $competition->rounds()
                ->active()
                ->orderBy('round_number')
                ->with(['matches'])
                ->get();
        }

        // Get registered teams/participants
        $registrations = $competition->registrations()
            ->where('status', 'confirmed')
            ->with(['user', 'teamMembers'])
            ->get();

        return view('competitions.rounds.index', compact('competition', 'rounds', 'registrations'));
    }

    /**
     * Menampilkan detail babak tertentu dengan hasil
     * Similar to caturnawa-24.tams.my.id/matalomba/sm/final/detailAGRI%20CINEMA
     */
    public function show(Competition $competition, CompetitionRound $round)
    {
        // Load round dengan relasi yang diperlukan
        $round->load(['matches.teamMatchups', 'competition']);

        // Get participants untuk round ini
        $participants = $this->getRoundParticipants($competition, $round);

        // Get scores untuk round ini
        $scores = $this->getRoundScores($competition, $round);

        // Calculate rankings
        $rankings = $this->calculateRankings($participants, $scores, $competition);

        return view('competitions.rounds.show', compact('competition', 'round', 'participants', 'scores', 'rankings'));
    }

    /**
     * Menampilkan detail peserta individual
     * Similar to caturnawa-24.tams.my.id/matalomba/sm/final/detailDi%20Balik%20Kandang
     */
    public function participantDetail(Competition $competition, CompetitionRound $round, Registration $registration)
    {
        // Load registration dengan relasi yang diperlukan
        $registration->load(['user', 'teamMembers', 'submissions', 'scores']);

        // Get scores untuk peserta ini di round ini
        $participantScores = Score::where('competition_id', $competition->id)
            ->where('registration_id', $registration->id)
            ->where('round_id', $round->id)
            ->with(['judge', 'criteria'])
            ->get();

        // Get submission untuk round ini
        $submission = Submission::where('competition_id', $competition->id)
            ->where('registration_id', $registration->id)
            ->where('round_type', $round->round_type)
            ->with(['files', 'comments'])
            ->first();

        return view('competitions.rounds.participant-detail', compact(
            'competition', 
            'round', 
            'registration', 
            'participantScores', 
            'submission'
        ));
    }

    /**
     * Menampilkan hasil final kompetisi
     * Similar to caturnawa-24.tams.my.id/matalomba/sm/final
     */
    public function finalResults(Competition $competition)
    {
        // Get final round
        $finalRound = $competition->rounds()
            ->where('round_type', 'final')
            ->first();

        if (!$finalRound) {
            abort(404, 'Final round not found');
        }

        // Get all participants yang lolos ke final
        $finalParticipants = $this->getRoundParticipants($competition, $finalRound);

        // Get final scores
        $finalScores = $this->getRoundScores($competition, $finalRound);

        // Calculate final rankings
        $finalRankings = $this->calculateRankings($finalParticipants, $finalScores, $competition);

        // Get competition statistics
        $stats = $this->getCompetitionStatistics($competition);

        return view('competitions.rounds.final-results', compact(
            'competition', 
            'finalRound', 
            'finalParticipants', 
            'finalScores', 
            'finalRankings',
            'stats'
        ));
    }

    /**
     * Membuat default rounds untuk kompetisi
     */
    private function createDefaultRounds(Competition $competition)
    {
        $defaultRounds = [
            [
                'round_type' => 'penyisihan',
                'name' => 'Babak Penyisihan',
                'description' => 'Babak awal kompetisi',
                'round_number' => 1,
                'status' => 'upcoming',
                'is_active' => true,
            ],
            [
                'round_type' => 'semifinal',
                'name' => 'Semifinal',
                'description' => 'Babak semifinal',
                'round_number' => 2,
                'status' => 'upcoming',
                'is_active' => true,
            ],
            [
                'round_type' => 'final',
                'name' => 'Final',
                'description' => 'Babak final',
                'round_number' => 3,
                'status' => 'upcoming',
                'is_active' => true,
            ],
        ];

        foreach ($defaultRounds as $roundData) {
            $roundData['competition_id'] = $competition->id;
            CompetitionRound::create($roundData);
        }
    }

    /**
     * Mendapatkan peserta untuk round tertentu
     */
    private function getRoundParticipants(Competition $competition, CompetitionRound $round)
    {
        // Untuk round pertama, ambil semua peserta terdaftar
        if ($round->round_number == 1) {
            return $competition->registrations()
                ->where('status', 'confirmed')
                ->with(['user', 'teamMembers'])
                ->get();
        }

        // Untuk round selanjutnya, ambil peserta yang lolos dari round sebelumnya
        // TODO: Implement logic untuk menentukan peserta yang lolos
        return collect();
    }

    /**
     * Mendapatkan scores untuk round tertentu
     */
    private function getRoundScores(Competition $competition, CompetitionRound $round)
    {
        return Score::where('competition_id', $competition->id)
            ->where('round_id', $round->id)
            ->with(['registration.user', 'registration.teamMembers', 'judge', 'criteria'])
            ->get();
    }

    /**
     * Menghitung ranking berdasarkan scores
     */
    private function calculateRankings($participants, $scores, $competition)
    {
        $rankings = [];

        foreach ($participants as $participant) {
            $participantScores = $scores->where('registration_id', $participant->id);
            
            // Calculate total score based on competition type
            $totalScore = $this->calculateTotalScore($participantScores, $competition);
            
            $rankings[] = [
                'registration' => $participant,
                'total_score' => $totalScore,
                'scores' => $participantScores,
            ];
        }

        // Sort by total score descending
        usort($rankings, function ($a, $b) {
            return $b['total_score'] <=> $a['total_score'];
        });

        // Add rank position
        foreach ($rankings as $index => &$ranking) {
            $ranking['rank'] = $index + 1;
        }

        return $rankings;
    }

    /**
     * Menghitung total score berdasarkan tipe kompetisi
     */
    private function calculateTotalScore($scores, $competition)
    {
        if ($scores->isEmpty()) {
            return 0;
        }

        // Implementasi berbeda berdasarkan tipe kompetisi
        if ($competition->isSpcCompetition()) {
            return $this->calculateSpcScore($scores);
        } elseif ($competition->isEdcCompetition() || $competition->isKdbiCompetition()) {
            return $this->calculateDebateScore($scores);
        }

        // Default: rata-rata semua scores
        return $scores->avg('score');
    }

    /**
     * Menghitung score SPC berdasarkan CSV reference
     */
    private function calculateSpcScore($scores)
    {
        // Implementasi berdasarkan CONTOH AKUMULASI NILAI AKHIR SPC 25.csv
        // Bobot: Naskah (Semifinal) 60%, Presentasi (Final) 40%
        
        $naskahScores = $scores->where('criteria_type', 'naskah');
        $presentasiScores = $scores->where('criteria_type', 'presentasi');
        
        $avgNaskah = $naskahScores->avg('score') ?? 0;
        $avgPresentasi = $presentasiScores->avg('score') ?? 0;
        
        return ($avgNaskah * 0.6) + ($avgPresentasi * 0.4);
    }

    /**
     * Menghitung score Debate berdasarkan CSV reference
     */
    private function calculateDebateScore($scores)
    {
        // Implementasi berdasarkan Tabulasi Penilaian_.csv
        // Menggunakan sistem individual dan team scores
        
        $individualScores = $scores->where('score_type', 'individual');
        $teamScores = $scores->where('score_type', 'team');
        
        // Calculate average team score
        return $teamScores->avg('score') ?? $individualScores->avg('score') ?? 0;
    }

    /**
     * Mendapatkan statistik kompetisi
     */
    private function getCompetitionStatistics(Competition $competition)
    {
        return [
            'total_participants' => $competition->registrations()->where('status', 'confirmed')->count(),
            'total_submissions' => $competition->submissions()->count(),
            'total_scores' => $competition->scores()->count(),
            'rounds_completed' => $competition->rounds()->where('status', 'completed')->count(),
        ];
    }
}
