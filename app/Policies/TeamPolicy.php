<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Team;
use App\Models\User;

class TeamPolicy
{
    public function create(User $user): bool
    {
        return true;
    }

    public function setCurrentTeam(User $user, Team $team): bool
    {
        return $user->teams->contains($team);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Team $team): bool
    {
        if (! $user->teams->contains($team)) {
            return false;
        }

        return $user->can('update team');
    }

    public function leave(User $user, Team $team): bool
    {
        if (! $user->teams->contains($team)) {
            return false;
        }

        return $user->teams->count() >= 2;
    }

    public function removeTeamMember(User $user, Team $team, User $member): bool
    {
        if ($user->id === $member->id) {
            return false;
        }

        if ($team->users->doesntContain($member)) {
            return false;
        }

        return $user->can('remove team members');
    }

    public function inviteToTeam(User $user, Team $team): bool
    {
        if ($user->teams->doesntContain($team)) {
            return false;
        }

        return $user->can('invite to team');
    }

    public function viewTeamMembers(User $user, Team $team): bool
    {
        return $user->can('view team members');
    }

    public function revokeInvite(User $user, Team $team): bool
    {
        return $user->can('revoke invitation');
    }

    public function changeMemberRole(User $user, Team $team, User $member): bool
    {
        if ($team->users->doesntContain($member)) {
            return false;
        }

        return $user->can('change member roles');
    }
}
