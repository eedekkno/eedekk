<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Support\Str;

trait Uuid
{
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model): void {
            $model->uuid = (string) Str::uuid();
        });
    }
}
