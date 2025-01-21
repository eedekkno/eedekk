<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use Illuminate\Database\Query\Builder;
use Illuminate\Validation\Rule;
use Livewire\Form;

class PriceForm extends Form
{
    public string $name = '';

    public ?string $price = null;

    public string $price_group_id;

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'price' => 'required|string|min:0',
            'price_group_id' => [
                'required',
                Rule::exists('price_groups', 'id')->where(function (Builder $query): void {
                    $query->where('team_id', auth()->user()->team->id);
                }),
            ],
        ];
    }
}
