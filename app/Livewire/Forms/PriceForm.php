<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use Illuminate\Validation\Rule;
use Livewire\Form;

class PriceForm extends Form
{
    public string $name = '';

    public ?string $price = '';

    public string $pricegroup_id;

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'price' => 'required|string|min:0',
            'pricegroup_id' => [
                'required',
                Rule::exists('pricegroups', 'id')->where(function ($query): void {
                    $query->where('team_id', auth()->user()->team->id);
                }),
            ],
        ];
    }
}
