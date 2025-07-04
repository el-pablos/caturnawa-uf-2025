#!/bin/bash

# Script untuk memperbaiki assignment juri ke kompetisi

set -e

PROJECT_DIR="/var/www/uf25.tams.my.id"

echo "🔧 Fixing Jury Assignments..."
echo "============================="

cd "$PROJECT_DIR"

# Load environment
source .env
export APP_ENV="$APP_ENV"
export APP_KEY="$APP_KEY" 
export DB_PASSWORD="$DB_PASSWORD"

echo "1. Checking current jury assignments..."
echo "======================================"

# Check current assignments
sudo -u www-data -E php artisan tinker --execute="
\$assignments = DB::table('competition_juries')->count();
echo 'Current jury assignments: ' . \$assignments;
"

echo ""
echo "2. Assigning all juries to all competitions..."
echo "============================================="

# Assign all juries to all competitions
sudo -u www-data -E php artisan tinker --execute="
\$juries = App\Models\User::whereHas('roles', function(\$q) { \$q->where('name', 'Juri'); })->get();
\$competitions = App\Models\Competition::where('is_active', true)->get();

echo 'Found ' . \$juries->count() . ' juries';
echo 'Found ' . \$competitions->count() . ' active competitions';

\$assigned = 0;
foreach(\$competitions as \$competition) {
    foreach(\$juries as \$jury) {
        \$exists = DB::table('competition_juries')
            ->where('competition_id', \$competition->id)
            ->where('user_id', \$jury->id)
            ->exists();
            
        if(!\$exists) {
            DB::table('competition_juries')->insert([
                'competition_id' => \$competition->id,
                'user_id' => \$jury->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            \$assigned++;
            echo 'Assigned jury ' . \$jury->name . ' to competition ' . \$competition->name;
        }
    }
}

echo 'Total new assignments created: ' . \$assigned;
"

echo ""
echo "3. Verifying assignments..."
echo "=========================="

# Verify assignments
sudo -u www-data -E php artisan tinker --execute="
\$assignments = DB::table('competition_juries')->count();
echo 'Total jury assignments after fix: ' . \$assignments;

\$juries = App\Models\User::whereHas('roles', function(\$q) { \$q->where('name', 'Juri'); })->get();
foreach(\$juries as \$jury) {
    \$competitionCount = \$jury->juryCompetitions()->count();
    echo 'Jury ' . \$jury->name . ' assigned to ' . \$competitionCount . ' competitions';
}
"

echo ""
echo "4. Testing jury submission visibility..."
echo "======================================="

# Test submission visibility
sudo -u www-data -E php artisan tinker --execute="
\$jury = App\Models\User::whereHas('roles', function(\$q) { \$q->where('name', 'Juri'); })->first();
if(\$jury) {
    echo 'Testing with jury: ' . \$jury->name;
    
    \$submissions = App\Models\Submission::with(['registration.user', 'registration.competition'])
        ->whereHas('registration.competition.juries', function (\$q) use (\$jury) {
            \$q->where('user_id', \$jury->id);
        })
        ->where(function(\$q) {
            \$q->where('status', 'submitted')
              ->orWhere(function(\$subQ) {
                  \$subQ->where('is_final', true);
              });
        })
        ->get();
        
    echo 'Submissions now visible to jury: ' . \$submissions->count();
    foreach(\$submissions as \$submission) {
        echo 'ID: ' . \$submission->id . ' | Title: ' . \$submission->title . ' | Status: ' . \$submission->status . ' | is_final: ' . (\$submission->is_final ? 'true' : 'false');
    }
} else {
    echo 'No jury found!';
}
"

echo ""
echo "5. Fixing submission status if needed..."
echo "======================================="

# Fix submission status
sudo -u www-data -E php artisan tinker --execute="
\$fixed = 0;
\$submissions = App\Models\Submission::whereNull('status')->orWhere('status', '')->get();
foreach(\$submissions as \$submission) {
    if(\$submission->is_final && \$submission->submitted_at) {
        \$submission->status = 'submitted';
        \$submission->save();
        \$fixed++;
        echo 'Fixed submission ID ' . \$submission->id . ' status to submitted';
    } elseif(!\$submission->is_final) {
        \$submission->status = 'draft';
        \$submission->save();
        \$fixed++;
        echo 'Fixed submission ID ' . \$submission->id . ' status to draft';
    }
}
echo 'Total submissions status fixed: ' . \$fixed;
"

echo ""
echo "✅ Jury assignment fix completed!"
echo "================================"
echo ""
echo "📋 Summary:"
echo "- All juries are now assigned to all active competitions"
echo "- Submission statuses have been fixed"
echo "- Juries should now be able to see submitted works"
echo ""
echo "🔗 Test by visiting: /juri/submissions"
