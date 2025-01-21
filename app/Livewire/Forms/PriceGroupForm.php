<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class PriceGroupForm extends Form
{
    #[Validate('required')]
    public string $name = '';
}
