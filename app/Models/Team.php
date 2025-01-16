<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\TeamStatus;
use App\Observers\TeamObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property TeamStatus $status
 */
#[ObservedBy(TeamObserver::class)]
class Team extends Model
{
    /** @use HasFactory<\Database\Factories\TeamFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'zipcode',
        'city',
        'org_number',
        'phone',
        'email',
        'price',
        'trial_ends_at',
        'subscription_ends_at',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<\App\Models\User, $this>
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\TeamInvite, $this>
     */
    public function invites(): HasMany
    {
        return $this->hasMany(TeamInvite::class);
    }

    public function isPremium(): bool
    {
        return $this->status === TeamStatus::PREMIUM;
    }

    public function isOverdue(): bool
    {
        return $this->isPremium() && now()->subDays(20) > $this->subscription_ends_at;
    }

    public function freeTrialDaysLeft(): float
    {
        return now()->diffInDays($this->trial_ends_at);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\Customer, $this>
     */
    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\Price, $this>
     */
    public function prices(): HasMany
    {
        return $this->hasMany(Price::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\Pricegroup, $this>
     */
    public function pricegroups(): HasMany
    {
        return $this->hasMany(Pricegroup::class);
    }

    protected function casts(): array
    {
        return [
            'status' => TeamStatus::class,
            'trial_ends_at' => 'datetime',
            'subscription_ends_at' => 'datetime',
        ];
    }
}
