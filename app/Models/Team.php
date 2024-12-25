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

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

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

    protected function casts(): array
    {
        return [
            'status' => TeamStatus::class,
            'trial_ends_at' => 'datetime',
            'subscription_ends_at' => 'datetime',
        ];
    }
}
