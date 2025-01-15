<?php

declare(strict_types=1);

namespace App\Livewire;

use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class CustomerIndex extends Component
{
    use WithPagination;

    #[Layout('layouts.app')]
    public function render(): View
    {
        return view('livewire.customers.index', [
            'customers' => auth()->user()->team->customers()->orderBy('name', 'asc')->paginate(25),
        ]);
    }
}
