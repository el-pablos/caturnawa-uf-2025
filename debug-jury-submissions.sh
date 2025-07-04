#!/bin/bash

# Debug script untuk masalah juri tidak bisa melihat submission

set -e

PROJECT_DIR="/var/www/uf25.tams.my.id"

echo "🔍 Debugging Jury Submissions Issue..."
echo "=================================="

cd "$PROJECT_DIR"

# Load environment
source .env
export APP_ENV="$APP_ENV"
export APP_KEY="$APP_KEY" 
export DB_PASSWORD="$DB_PASSWORD"

echo "1. Checking submissions in database..."
echo "======================================"

# Check all submissions
echo "📊 All submissions:"
sudo -u www-data -E php artisan tinker --execute="
\$submissions = App\Models\Submission::with(['registration.user', 'registration.competition'])->get();
echo 'Total submissions: ' . \$submissions->count();
foreach(\$submissions as \$submission) {
    echo 'ID: ' . \$submission->id . ' | Title: ' . \$submission->title . ' | Status: ' . \$submission->status . ' | is_final: ' . (\$submission->is_final ? 'true' : 'false') . ' | submitted_at: ' . \$submission->submitted_at . ' | User: ' . \$submission->registration->user->name . ' | Competition: ' . \$submission->registration->competition->name;
}
"

echo ""
echo "2. Checking juries and their assigned competitions..."
echo "=================================================="

# Check juries
echo "👨‍⚖️ All juries:"
sudo -u www-data -E php artisan tinker --execute="
\$juries = App\Models\User::whereHas('roles', function(\$q) { \$q->where('name', 'Juri'); })->get();
echo 'Total juries: ' . \$juries->count();
foreach(\$juries as \$jury) {
    echo 'Jury ID: ' . \$jury->id . ' | Name: ' . \$jury->name . ' | Email: ' . \$jury->email;
}
"

echo ""
echo "3. Checking competition_juries table..."
echo "======================================"

# Check competition juries assignments
sudo -u www-data -E php artisan tinker --execute="
\$assignments = DB::table('competition_juries')->get();
echo 'Total jury assignments: ' . \$assignments->count();
foreach(\$assignments as \$assignment) {
    \$jury = App\Models\User::find(\$assignment->user_id);
    \$competition = App\Models\Competition::find(\$assignment->competition_id);
    echo 'Jury: ' . (\$jury ? \$jury->name : 'Not found') . ' | Competition: ' . (\$competition ? \$competition->name : 'Not found');
}
"

echo ""
echo "4. Testing jury query for submissions..."
echo "======================================"

# Test jury query
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
                  \$subQ->where('is_final', true)
                       ->whereNull('status');
              });
        })
        ->whereNotNull('submitted_at')
        ->get();
        
    echo 'Submissions visible to jury: ' . \$submissions->count();
    foreach(\$submissions as \$submission) {
        echo 'ID: ' . \$submission->id . ' | Title: ' . \$submission->title . ' | Status: ' . \$submission->status . ' | Competition: ' . \$submission->registration->competition->name;
    }
} else {
    echo 'No jury found!';
}
"

echo ""
echo "5. Checking specific submission details..."
echo "========================================"

# Check latest submission details
sudo -u www-data -E php artisan tinker --execute="
\$latest = App\Models\Submission::latest()->first();
if(\$latest) {
    echo 'Latest submission details:';
    echo 'ID: ' . \$latest->id;
    echo 'Title: ' . \$latest->title;
    echo 'Status: ' . \$latest->status;
    echo 'is_final: ' . (\$latest->is_final ? 'true' : 'false');
    echo 'submitted_at: ' . \$latest->submitted_at;
    echo 'User: ' . \$latest->registration->user->name;
    echo 'Competition ID: ' . \$latest->registration->competition_id;
    echo 'Competition Name: ' . \$latest->registration->competition->name;
    
    echo '';
    echo 'Checking if any jury is assigned to this competition:';
    \$juries = \$latest->registration->competition->juries;
    echo 'Juries assigned: ' . \$juries->count();
    foreach(\$juries as \$jury) {
        echo 'Jury: ' . \$jury->name . ' (ID: ' . \$jury->id . ')';
    }
} else {
    echo 'No submissions found!';
}
"

echo ""
echo "6. Manual database check..."
echo "=========================="

# Direct database queries
echo "📊 Direct database queries:"

echo "Submissions table:"
mysql -h"$DB_HOST" -u"$DB_USERNAME" -p"$DB_PASSWORD" "$DB_DATABASE" -e "
SELECT id, title, status, is_final, submitted_at, registration_id 
FROM submissions 
ORDER BY created_at DESC 
LIMIT 5;
"

echo ""
echo "Competition juries table:"
mysql -h"$DB_HOST" -u"$DB_USERNAME" -p"$DB_PASSWORD" "$DB_DATABASE" -e "
SELECT cj.*, u.name as jury_name, c.name as competition_name 
FROM competition_juries cj 
LEFT JOIN users u ON cj.user_id = u.id 
LEFT JOIN competitions c ON cj.competition_id = c.id;
"

echo ""
echo "Registrations table:"
mysql -h"$DB_HOST" -u"$DB_USERNAME" -p"$DB_PASSWORD" "$DB_DATABASE" -e "
SELECT r.id, r.user_id, r.competition_id, r.status, u.name as user_name, c.name as competition_name
FROM registrations r
LEFT JOIN users u ON r.user_id = u.id
LEFT JOIN competitions c ON r.competition_id = c.id
WHERE r.id IN (SELECT registration_id FROM submissions)
ORDER BY r.created_at DESC
LIMIT 5;
"

echo ""
echo "🎯 Debug completed!"
echo "=================="
echo ""
echo "📋 Next steps:"
echo "1. Check if juries are properly assigned to competitions"
echo "2. Verify submission status and is_final values"
echo "3. Ensure registration status is 'confirmed'"
echo "4. Check if competition_juries table has correct data"
