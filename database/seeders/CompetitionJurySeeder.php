<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Competition;
use Illuminate\Support\Facades\DB;

class CompetitionJurySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all juries
        $juries = User::whereHas('roles', function($query) {
            $query->where('name', 'Juri');
        })->get();

        // Get all competitions
        $competitions = Competition::all();

        // Assign juries to competitions
        foreach ($competitions as $competition) {
            foreach ($juries as $jury) {
                DB::table('competition_juries')->updateOrInsert([
                    'competition_id' => $competition->id,
                    'user_id' => $jury->id,
                ], [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
