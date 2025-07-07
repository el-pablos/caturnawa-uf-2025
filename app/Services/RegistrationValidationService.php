<?php

namespace App\Services;

use App\Models\Registration;
use App\Models\Competition;
use App\Models\User;
use Illuminate\Support\Collection;

/**
 * Service untuk validasi registrasi kompetisi
 * 
 * Menangani pengecekan duplikasi peserta dan auto lock
 * untuk mencegah peserta mendaftar di multiple kompetisi
 */
class RegistrationValidationService
{
    /**
     * Check if user or team members are already registered in other competitions
     *
     * @param User $user
     * @param Competition $competition
     * @param array $teamMembers
     * @return array
     */
    public function checkRegistrationConflicts(User $user, Competition $competition, array $teamMembers = []): array
    {
        $conflicts = [];

        // Check if user is already registered in other competitions
        $userConflict = $this->checkUserConflict($user, $competition);
        if ($userConflict) {
            $conflicts[] = $userConflict;
        }

        // Check if any team member is already registered in other competitions
        $teamConflicts = $this->checkTeamMemberConflicts($teamMembers, $competition);
        $conflicts = array_merge($conflicts, $teamConflicts);

        return $conflicts;
    }

    /**
     * Check if user is already registered in other competitions
     *
     * @param User $user
     * @param Competition $competition
     * @return array|null
     */
    private function checkUserConflict(User $user, Competition $competition): ?array
    {
        $existingRegistration = Registration::where('user_id', $user->id)
            ->where('competition_id', '!=', $competition->id)
            ->whereIn('status', ['confirmed', 'pending'])
            ->with('competition')
            ->first();

        if ($existingRegistration) {
            return [
                'type' => 'user',
                'email' => $user->email,
                'name' => $user->name,
                'competition' => $existingRegistration->competition->name,
                'status' => $existingRegistration->status,
                'message' => "Anda sudah terdaftar di kompetisi {$existingRegistration->competition->name}"
            ];
        }

        return null;
    }

    /**
     * Check if any team member is already registered in other competitions
     *
     * @param array $teamMembers
     * @param Competition $competition
     * @return array
     */
    private function checkTeamMemberConflicts(array $teamMembers, Competition $competition): array
    {
        $conflicts = [];

        if (empty($teamMembers)) {
            return $conflicts;
        }

        // Get all confirmed registrations from other competitions
        $existingRegistrations = Registration::where('competition_id', '!=', $competition->id)
            ->whereIn('status', ['confirmed', 'pending'])
            ->whereNotNull('team_members')
            ->with('competition', 'user')
            ->get();

        foreach ($teamMembers as $member) {
            if (!isset($member['email']) || !isset($member['name'])) {
                continue;
            }

            $memberEmail = strtolower(trim($member['email']));
            $memberName = strtolower(trim($member['name']));

            // Check against user emails (team leaders)
            $userConflict = $existingRegistrations->first(function($registration) use ($memberEmail) {
                return strtolower($registration->user->email) === $memberEmail;
            });

            if ($userConflict) {
                $conflicts[] = [
                    'type' => 'team_member_as_leader',
                    'email' => $member['email'],
                    'name' => $member['name'],
                    'competition' => $userConflict->competition->name,
                    'status' => $userConflict->status,
                    'message' => "{$member['name']} ({$member['email']}) sudah terdaftar sebagai ketua tim di kompetisi {$userConflict->competition->name}"
                ];
                continue;
            }

            // Check against team members
            foreach ($existingRegistrations as $registration) {
                if (!$registration->team_members || !is_array($registration->team_members)) {
                    continue;
                }

                foreach ($registration->team_members as $existingMember) {
                    if (!isset($existingMember['email']) || !isset($existingMember['name'])) {
                        continue;
                    }

                    $existingEmail = strtolower(trim($existingMember['email']));
                    $existingName = strtolower(trim($existingMember['name']));

                    // Check by email (primary check)
                    if ($existingEmail === $memberEmail) {
                        $conflicts[] = [
                            'type' => 'team_member',
                            'email' => $member['email'],
                            'name' => $member['name'],
                            'competition' => $registration->competition->name,
                            'status' => $registration->status,
                            'existing_name' => $existingMember['name'],
                            'message' => "{$member['name']} ({$member['email']}) sudah terdaftar sebagai anggota tim di kompetisi {$registration->competition->name}"
                        ];
                        break 2; // Break both loops
                    }

                    // Check by name (secondary check for similar names)
                    if ($existingName === $memberName && $existingEmail !== $memberEmail) {
                        $conflicts[] = [
                            'type' => 'team_member_name_similar',
                            'email' => $member['email'],
                            'name' => $member['name'],
                            'competition' => $registration->competition->name,
                            'status' => $registration->status,
                            'existing_email' => $existingMember['email'],
                            'message' => "Nama {$member['name']} sudah terdaftar di kompetisi {$registration->competition->name} dengan email berbeda ({$existingMember['email']}). Pastikan tidak ada duplikasi peserta."
                        ];
                        break 2; // Break both loops
                    }
                }
            }
        }

        return $conflicts;
    }

    /**
     * Check if registration is allowed
     *
     * @param User $user
     * @param Competition $competition
     * @param array $teamMembers
     * @return bool
     */
    public function isRegistrationAllowed(User $user, Competition $competition, array $teamMembers = []): bool
    {
        $conflicts = $this->checkRegistrationConflicts($user, $competition, $teamMembers);
        
        // Only allow registration if there are no conflicts
        // or only name similarity warnings (not email conflicts)
        $blockingConflicts = array_filter($conflicts, function($conflict) {
            return $conflict['type'] !== 'team_member_name_similar';
        });

        return empty($blockingConflicts);
    }

    /**
     * Get formatted error messages for conflicts
     *
     * @param array $conflicts
     * @return array
     */
    public function getConflictMessages(array $conflicts): array
    {
        $messages = [];
        $warnings = [];

        foreach ($conflicts as $conflict) {
            if ($conflict['type'] === 'team_member_name_similar') {
                $warnings[] = $conflict['message'];
            } else {
                $messages[] = $conflict['message'];
            }
        }

        return [
            'errors' => $messages,
            'warnings' => $warnings
        ];
    }

    /**
     * Get user's current registrations
     *
     * @param User $user
     * @return Collection
     */
    public function getUserRegistrations(User $user): Collection
    {
        return Registration::where('user_id', $user->id)
            ->whereIn('status', ['confirmed', 'pending'])
            ->with('competition')
            ->get();
    }

    /**
     * Check if user can register for any competition
     *
     * @param User $user
     * @return bool
     */
    public function canUserRegisterForAnyCompetition(User $user): bool
    {
        $existingRegistrations = $this->getUserRegistrations($user);
        return $existingRegistrations->isEmpty();
    }
}
