<?php

declare(strict_types=1);

namespace App\Livewire\Components;

use App\Models\Team;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;

class ChangeTeamDropdown extends Component
{
    /** @var Collection<int, Team> */
    public Collection $teams;

    public function mount(): void
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        /** @var Collection<int, \App\Models\Team> $teams */
        $teams = $user->teams;

        $this->teams = $teams;
    }

    /**
     * Change the current team for the authenticated user.
     *
     * @param  \App\Models\Team  $team  The team to switch to.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the team is not found in the user's teams.
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException If the user is not authorized to set the current team.
     */
    public function changeTeam(Team $team): void
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        /** @var \App\Models\Team $team */
        $team = $user->teams()->findOrFail($team->id);

        abort_unless($user->can('setCurrentTeam', $team), 403, 'Du har ikke tillatelse til å bytte til dette teamet.');

        $user->team()->associate($team)->save();

        flash()->success('Changed department.');

        $this->dispatch('teamChanged');
    }

    public function render(): View
    {
        return view('livewire.components.change-team-dropdown');
    }
}
