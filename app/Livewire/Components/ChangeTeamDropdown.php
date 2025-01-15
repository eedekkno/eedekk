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
    public $teams;

    public function mount(): void
    {
        $this->teams = auth()->user()->teams;
    }

    public function changeTeam(Team $team): void
    {
        $user = auth()->user();

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
