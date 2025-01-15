<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Livewire\Forms\CustomerForm;
use App\Models\Customer;
use HelgeSverre\Telefonkatalog\Facades\Telefonkatalog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\Features\SupportRedirects\Redirector;

class CustomerCreate extends Component
{
    public ?Customer $customer = null;

    public CustomerForm $form;

    /** @var array<int, mixed>|null */
    public $persons = null;

    /** @codeCoverageIgnore  */
    public function lookupPhone(string $field): void
    {
        $phone = $this->form->{$field};

        $cleanName = Str::slug($phone, '_');
        $cacheKey = "telefonkatalog:{$cleanName}";

        $this->persons = null;

        $person = Cache::remember($cacheKey, 60, fn () => Telefonkatalog::search($phone));

        if ($person) {
            $this->persons = $person;
        } else {
            $this->addError('form.'.$field, 'Ingen resultater funnet på dette søket.');
        }
    }

    public function selectPerson(int $index): void
    {
        if (! isset($this->persons[$index])) {
            $this->addError('form.name', 'Person not found at index '.$index);

            return;
        }

        $person = $this->persons[$index];

        $this->form->name = $person->name ?? '';
        $this->form->address = $person->address ?? null;
        $this->form->phone = $person->phone ?? null;
        $this->form->email = $person->email ?? null;
        $this->form->zip = $person->postalCode ?? null;
        $this->form->city = $person->city ?? null;

        $this->reset('persons');
    }

    public function resetPersons(): void
    {
        $this->reset('persons');
    }

    public function mount(): void
    {
        if (! is_null($this->customer)) {
            $this->authorize('updateCustomer', [$this->customer->team, $this->customer]);
            $this->form->fill($this->customer->toArray());
        }
    }

    public function createCustomer(): Redirector|RedirectResponse
    {
        $this->form->validate();

        if (! is_null($this->customer)) {
            $this->customer->update($this->form->only([
                'name',
                'address',
                'zip',
                'city',
                'phone',
                'email',
                'type',
            ]));

            // broadcast(new OrderUpdated($this->order))->toOthers();

            flash()->success('Customer updated successfully.');

            return redirect()->route('customers.index');

        }

        $customer = auth()->user()->team->customers()->create($this->form->only([
            'name',
            'address',
            'zip',
            'city',
            'phone',
            'email',
            'type',
        ]));

        flash()->success('Customer created successfully.');

        return redirect()->route('customers.index');

    }

    #[Layout('layouts.app')]
    public function render(): View
    {
        return view('livewire.customers.create');
    }
}
