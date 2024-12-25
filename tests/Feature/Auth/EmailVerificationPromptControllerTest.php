<?php

declare(strict_types=1);

use App\Models\User;

use function Pest\Laravel\actingAs;

it('redirects to intended route if email is verified', function () {
    $user = User::factory()->create();

    actingAs($user);

    session(['url.intended' => route('dashboard')]);

    $response = $this->get(route('verification.notice'));

    $response->assertRedirect(route('dashboard'));
});

it('shows the verification view if email is not verified', function () {
    $user = User::factory()->unverified()->create();

    actingAs($user)
        ->get(route('verification.notice'))
        ->assertViewIs('auth.verify-email');
});
