<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use App\Enums\CustomerType;
use Illuminate\Validation\Rules\Enum;
use Livewire\Attributes\Validate;
use Livewire\Form;

class CustomerForm extends Form
{
    #[Validate('required|string')]
    public string $name = '';

    #[Validate('nullable|string')]
    public ?string $address = null;

    #[Validate('nullable|string')]
    public ?string $zip = null;

    #[Validate('nullable|string')]
    public ?string $city = null;

    #[Validate('nullable|string')]
    public ?string $phone = null;

    #[Validate('nullable|string|email')]
    public ?string $email = null;

    #[Validate('required', new Enum(CustomerType::class))]
    public string $type = CustomerType::PRIVATE->value;
}
