<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\TeamRole;
use App\Http\Requests\TeamLeaveRequest;
use App\Http\Requests\TeamStoreRequest;
use App\Http\Requests\TeamUpdateRequest;
use App\Models\Team;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class TeamController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('team.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TeamStoreRequest $request): RedirectResponse
    {
        $request->user()->teams()->attach($team = Team::create($request->validated()));

        $request->user()->team()->associate($team)->save();

        setPermissionsTeamId($team->id);

        $request->user()->assignRole(TeamRole::ADMIN->value);

        return redirect()->route('team.edit')->withStatus('team-updated');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request): View
    {
        return view('team.edit', [
            'team' => $request->user()->team->load('users.roles', 'invites.team', 'invites.invitedBy'),
            'roles' => Role::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TeamUpdateRequest $request, Team $team): RedirectResponse
    {
        $team->update($request->validated());

        return back()->withStatus('team-updated');
    }

    public function leave(TeamLeaveRequest $request, Team $team): RedirectResponse
    {
        $user = $request->user();
        $user->teams()->detach($team);
        $user->team()->associate($user->fresh()->teams->first())->save();

        return redirect()->route('dashboard');
    }
}
