<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\TeamRole;
use App\Http\Requests\TeamInviteDestroyRequest;
use App\Http\Requests\TeamInviteStoreRequest;
use App\Mail\TeamInvitation;
use App\Models\Team;
use App\Models\TeamInvite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class TeamInviteController extends Controller
{
    public function store(TeamInviteStoreRequest $request, Team $team)
    {
        $invite = $team->invites()->create([
            'email' => $request->email,
            'token' => str()->random(30),
            'invited_by' => auth()->id() ?? null,
        ]);

        Mail::to($request->email)->send(new TeamInvitation($invite));

        return back()->withStatus('team-invited');
    }

    public function destroy(TeamInviteDestroyRequest $request, Team $team, TeamInvite $teamInvite)
    {
        $teamInvite->delete();

        return redirect()->route('team.edit');
    }

    public function accept(Request $request)
    {
        $invite = TeamInvite::where('token', $request->token)->firstOrFail();

        $request->user()->teams()->attach($invite->team);

        setPermissionsTeamId($invite->team->id);

        $request->user()->assignRole(TeamRole::MEMBER);

        $request->user()->team()->associate($invite->team)->save();

        $invite->delete();

        return redirect()->route('dashboard');
    }
}
