<?php

namespace App\Services;

use App\Models\Competition;
use App\Models\Registration;
use App\Models\Payment;
use App\Models\Submission;
use App\Models\User;
use App\Models\Score;
use Illuminate\Support\Facades\DB;

/**
 * Service for global search across all entities
 */
class SearchService
{
    /**
     * Perform global search across all entities
     *
     * @param string $query
     * @param array $filters
     * @return array
     */
    public function globalSearch(string $query, array $filters = []): array
    {
        $results = [];

        // Search competitions
        if (!isset($filters['entity']) || $filters['entity'] === 'competitions') {
            $results['competitions'] = $this->searchCompetitions($query, $filters);
        }

        // Search registrations
        if (!isset($filters['entity']) || $filters['entity'] === 'registrations') {
            $results['registrations'] = $this->searchRegistrations($query, $filters);
        }

        // Search users
        if (!isset($filters['entity']) || $filters['entity'] === 'users') {
            $results['users'] = $this->searchUsers($query, $filters);
        }

        // Search submissions
        if (!isset($filters['entity']) || $filters['entity'] === 'submissions') {
            $results['submissions'] = $this->searchSubmissions($query, $filters);
        }

        // Search payments
        if (!isset($filters['entity']) || $filters['entity'] === 'payments') {
            $results['payments'] = $this->searchPayments($query, $filters);
        }

        return $results;
    }

    /**
     * Search competitions
     */
    public function searchCompetitions(string $query, array $filters = [])
    {
        $searchQuery = Competition::query();

        // Search in name, description
        $searchQuery->where(function($q) use ($query) {
            $q->where('name', 'like', "%{$query}%")
              ->orWhere('description', 'like', "%{$query}%")
              ->orWhere('category', 'like', "%{$query}%");
        });

        // Apply filters
        if (isset($filters['category'])) {
            $searchQuery->where('category', $filters['category']);
        }

        if (isset($filters['status'])) {
            $searchQuery->where('status', $filters['status']);
        }

        if (isset($filters['is_active'])) {
            $searchQuery->where('is_active', $filters['is_active']);
        }

        return $searchQuery->limit(10)->get();
    }

    /**
     * Search registrations
     */
    public function searchRegistrations(string $query, array $filters = [])
    {
        $searchQuery = Registration::with(['user', 'competition']);

        // Search in registration number, team name, user name, email
        $searchQuery->where(function($q) use ($query) {
            $q->where('registration_number', 'like', "%{$query}%")
              ->orWhere('team_name', 'like', "%{$query}%")
              ->orWhere('institution', 'like', "%{$query}%")
              ->orWhereHas('user', function($userQuery) use ($query) {
                  $userQuery->where('name', 'like', "%{$query}%")
                           ->orWhere('email', 'like', "%{$query}%");
              })
              ->orWhereHas('competition', function($compQuery) use ($query) {
                  $compQuery->where('name', 'like', "%{$query}%");
              });
        });

        // Apply filters
        if (isset($filters['competition_id'])) {
            $searchQuery->where('competition_id', $filters['competition_id']);
        }

        if (isset($filters['status'])) {
            $searchQuery->where('status', $filters['status']);
        }

        if (isset($filters['date_from'])) {
            $searchQuery->whereDate('registered_at', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $searchQuery->whereDate('registered_at', '<=', $filters['date_to']);
        }

        return $searchQuery->orderBy('registered_at', 'desc')->limit(10)->get();
    }

    /**
     * Search users
     */
    public function searchUsers(string $query, array $filters = [])
    {
        $searchQuery = User::with('roles');

        // Search in name, email, phone, institution
        $searchQuery->where(function($q) use ($query) {
            $q->where('name', 'like', "%{$query}%")
              ->orWhere('email', 'like', "%{$query}%")
              ->orWhere('phone', 'like', "%{$query}%")
              ->orWhere('institution', 'like', "%{$query}%")
              ->orWhere('student_id', 'like', "%{$query}%");
        });

        // Apply filters
        if (isset($filters['role'])) {
            $searchQuery->role($filters['role']);
        }

        if (isset($filters['participant_status'])) {
            $searchQuery->where('participant_status', $filters['participant_status']);
        }

        return $searchQuery->limit(10)->get();
    }

    /**
     * Search submissions
     */
    public function searchSubmissions(string $query, array $filters = [])
    {
        $searchQuery = Submission::with(['registration.user', 'registration.competition']);

        // Search in title, description, user name
        $searchQuery->where(function($q) use ($query) {
            $q->where('title', 'like', "%{$query}%")
              ->orWhere('description', 'like', "%{$query}%")
              ->orWhereHas('registration.user', function($userQuery) use ($query) {
                  $userQuery->where('name', 'like', "%{$query}%")
                           ->orWhere('email', 'like', "%{$query}%");
              })
              ->orWhereHas('registration.competition', function($compQuery) use ($query) {
                  $compQuery->where('name', 'like', "%{$query}%");
              });
        });

        // Apply filters
        if (isset($filters['competition_id'])) {
            $searchQuery->whereHas('registration', function($q) use ($filters) {
                $q->where('competition_id', $filters['competition_id']);
            });
        }

        if (isset($filters['status'])) {
            $searchQuery->where('status', $filters['status']);
        }

        if (isset($filters['is_final'])) {
            $searchQuery->where('is_final', $filters['is_final']);
        }

        return $searchQuery->orderBy('created_at', 'desc')->limit(10)->get();
    }

    /**
     * Search payments
     */
    public function searchPayments(string $query, array $filters = [])
    {
        $searchQuery = Payment::with(['registration.user', 'registration.competition']);

        // Search in order ID, transaction ID, user name
        $searchQuery->where(function($q) use ($query) {
            $q->where('order_id', 'like', "%{$query}%")
              ->orWhere('transaction_id', 'like', "%{$query}%")
              ->orWhereHas('registration.user', function($userQuery) use ($query) {
                  $userQuery->where('name', 'like', "%{$query}%")
                           ->orWhere('email', 'like', "%{$query}%");
              })
              ->orWhereHas('registration', function($regQuery) use ($query) {
                  $regQuery->where('registration_number', 'like', "%{$query}%");
              });
        });

        // Apply filters
        if (isset($filters['status'])) {
            $searchQuery->where('status', $filters['status']);
        }

        if (isset($filters['date_from'])) {
            $searchQuery->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $searchQuery->whereDate('created_at', '<=', $filters['date_to']);
        }

        return $searchQuery->orderBy('created_at', 'desc')->limit(10)->get();
    }

    /**
     * Get search statistics
     */
    public function getSearchStatistics(string $query): array
    {
        return [
            'competitions' => Competition::where('name', 'like', "%{$query}%")->count(),
            'registrations' => Registration::where('registration_number', 'like', "%{$query}%")
                ->orWhereHas('user', function($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%");
                })->count(),
            'users' => User::where('name', 'like', "%{$query}%")
                ->orWhere('email', 'like', "%{$query}%")->count(),
            'submissions' => Submission::where('title', 'like', "%{$query}%")->count(),
            'payments' => Payment::where('order_id', 'like', "%{$query}%")->count(),
        ];
    }
}

