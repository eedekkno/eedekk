<?php

declare(strict_types=1);

namespace App\Livewire;

use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class PriceIndex extends Component
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

    /** @var string[] */
    protected $listeners = [
        'price-updated' => '$refresh',
        'pricegroup-updated' => '$refresh',
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

        /**
         * @var \Illuminate\Contracts\Pagination\LengthAwarePaginator<\App\Models\Price>
         */
        $prices = $team->prices()
            ->with('pricegroup')
            ->join('pricegroups', 'prices.pricegroup_id', '=', 'pricegroups.id')
            ->when($this->search, function (Builder $query): void {
                $searchTerm = '%'.$this->search.'%';
                $query->where(function (Builder $query) use ($searchTerm): void {
                    $query->where('prices.name', 'like', $searchTerm)
                        ->orWhere('pricegroups.name', 'like', $searchTerm);
                });
            })
            ->orderBy('pricegroups.name', 'asc')
            ->orderBy('prices.price', 'asc')
            ->select('prices.*')
            ->paginate(20);

        return view('livewire.prices.index', [
            'prices' => $prices,
        ]);
    }
}
