<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Price extends Model
{
    /** @use HasFactory<\Database\Factories\PriceFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'price',
        'team_id',
        'pricegroup_id',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\Team, $this>
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\Pricegroup, $this>
     */
    public function pricegroup(): BelongsTo
    {
        return $this->belongsTo(Pricegroup::class);
    }

    /**
     * @return Attribute<int|float, int|float>
     */
    protected function price(): Attribute
    {
        return Attribute::make(
            get: fn (?int $value): int|float => $value !== null ? $value / 100 : 0,
            set: fn (int|float|null $value): int => $value !== null ? (int) ((float) $value * 100) : 0,
        );
    }
}
