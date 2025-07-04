<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Competition;
use App\Models\Submission;
use Illuminate\Support\Facades\DB;

class FixJuryAssignmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        echo "🔧 Fixing jury assignments and submission status...\n";
        
        // 1. Assign all juries to all active competitions
        $juries = User::whereHas('roles', function($query) {
            $query->where('name', 'Juri');
        })->get();
        
        $competitions = Competition::where('is_active', true)->get();
        
        echo "Found {$juries->count()} juries and {$competitions->count()} active competitions\n";
        
        $assignmentCount = 0;
        foreach ($competitions as $competition) {
            foreach ($juries as $jury) {
                $exists = DB::table('competition_juries')
                    ->where('competition_id', $competition->id)
                    ->where('user_id', $jury->id)
                    ->exists();
                    
                if (!$exists) {
                    DB::table('competition_juries')->insert([
                        'competition_id' => $competition->id,
                        'user_id' => $jury->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    $assignmentCount++;
                    echo "✅ Assigned jury {$jury->name} to competition {$competition->name}\n";
                }
            }
        }
        
        echo "Created {$assignmentCount} new jury assignments\n\n";
        
        // 2. Fix submission status
        echo "🔧 Fixing submission status...\n";
        
        $submissions = Submission::whereNull('status')->orWhere('status', '')->get();
        $fixedCount = 0;
        
        foreach ($submissions as $submission) {
            if ($submission->is_final && $submission->submitted_at) {
                $submission->status = 'submitted';
                $submission->save();
                $fixedCount++;
                echo "✅ Fixed submission ID {$submission->id} status to 'submitted'\n";
            } elseif (!$submission->is_final) {
                $submission->status = 'draft';
                $submission->save();
                $fixedCount++;
                echo "✅ Fixed submission ID {$submission->id} status to 'draft'\n";
            }
        }
        
        echo "Fixed {$fixedCount} submission statuses\n\n";
        
        // 3. Test jury visibility
        echo "🧪 Testing jury submission visibility...\n";
        
        $firstJury = $juries->first();
        if ($firstJury) {
            $visibleSubmissions = Submission::with(['registration.user', 'registration.competition'])
                ->whereHas('registration.competition.juries', function ($q) use ($firstJury) {
                    $q->where('user_id', $firstJury->id);
                })
                ->where(function($q) {
                    $q->where('status', 'submitted')
                      ->orWhere(function($subQ) {
                          $subQ->where('is_final', true);
                      });
                })
                ->get();
                
            echo "Jury {$firstJury->name} can now see {$visibleSubmissions->count()} submissions\n";
            
            foreach ($visibleSubmissions as $submission) {
                echo "  - {$submission->title} (Status: {$submission->status}, Final: " . ($submission->is_final ? 'Yes' : 'No') . ")\n";
            }
        }
        
        echo "\n✅ Jury assignment fix completed!\n";
    }
}
