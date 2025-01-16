<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\TeamMemberDestroyRequest;
use App\Http\Requests\TeamMemberUpdateRequest;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\RedirectResponse;

class TeamMemberController extends Controller
{
    /**
     * Update the role of a team member.
     */
    public function update(TeamMemberUpdateRequest $request, Team $team, User $user): RedirectResponse
    {
        if ($request->has('role')) {
            tap($team->users->find($user), function (?User $member) use ($request): void {
                $member->roles()->detach();
                $member->assignRole($request->role);
            });
        }

        return back();
    }

    /**
     * Remove a team member from a team.
     */
    public function destroy(TeamMemberDestroyRequest $request, Team $team, User $user): RedirectResponse
    {
        $team->users()->detach($user);
        $user->team()->associate($user->teams()->first())->save();
        $user->roles()->detach();

        return redirect()->route('team.edit');
    }
}
