<?php

declare(strict_types=1);

use App\Models\User;

use function Pest\Laravel\actingAs;

it('sends a verification email if user has not verified email', function (): void {
    $user = User::factory()->unverified()->create();

    actingAs($user)
        ->post(route('verification.send'))
        ->assertSessionHas('status', 'verification-link-sent')
        ->assertRedirect();
});

it('redirects to dashboard if user has already verified email', function (): void {
    $user = User::factory()->create(['email_verified_at' => now()]);

    actingAs($user)->post(route('verification.send'))
        ->assertRedirect(route('dashboard'));
});
