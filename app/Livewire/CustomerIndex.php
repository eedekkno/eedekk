<?php

declare(strict_types=1);

namespace App\Livewire;

use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class CustomerIndex extends Component
{
    use WithPagination;

    public ?string $search = null;

    /**
     * @var array<string, array<string, mixed>>
     */
    protected array $queryString = [
        'search' => [
            'except' => '',
        ],
    ];

    #[Layout('layouts.app')]
    public function render(): View
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        /**
         * @var \App\Models\Team $team
         */
        $team = $user->team;

        $searchTerm = '%'.$this->search.'%';

        /**
         * @var \Illuminate\Contracts\Pagination\LengthAwarePaginator<\App\Models\Customer>
         */
        $customers = $team->customers()
            ->when($this->search, function (Builder $query) use ($searchTerm): void {
                $query->where(function (Builder $query) use ($searchTerm): void {
                    $query->where('name', 'like', $searchTerm)
                        ->orWhere('address', 'like', $searchTerm)
                        ->orWhere('city', 'like', $searchTerm)
                        ->orWhere('email', 'like', $searchTerm)
                        ->orWhere('phone', 'like', $searchTerm);
                });
            })
            ->orderBy('name', 'asc')
            ->paginate(25);

        return view('livewire.customers.index', [
            'customers' => $customers,
        ]);
    }
}
