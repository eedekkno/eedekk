<?php

declare(strict_types=1);

use App\Models\Pricegroup;
use App\Models\Team;

it('belongs to a team', function (): void {
    $team = Team::factory()->create();
    $price = Pricegroup::factory()->create(['team_id' => $team->id]);

    expect($price->team)->toBeInstanceOf(Team::class)
        ->and($price->team->id)->toBe($team->id);
});
