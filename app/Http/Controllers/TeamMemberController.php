<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\TeamMemberDestroyRequest;
use App\Http\Requests\TeamMemberUpdateRequest;
use App\Models\Team;
use App\Models\User;

class TeamMemberController extends Controller
{
    public function update(TeamMemberUpdateRequest $request, Team $team, User $user)
    {
        if ($request->has('role')) {
            tap($team->users->find($user), function (User $member) use ($request) {
                $member->roles()->detach();
                $member->assignRole($request->role);
            });
        }

        return back();
    }

    public function destroy(TeamMemberDestroyRequest $request, Team $team, User $user)
    {
        $team->users()->detach($user);
        $user->team()->associate($user->teams()->first())->save();
        $user->roles()->detach();

        return redirect()->route('team.edit');
    }
}
