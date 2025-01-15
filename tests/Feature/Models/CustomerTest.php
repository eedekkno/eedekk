<?php

declare(strict_types=1);

use App\Models\Customer;

it('should generate a uuid when creating a customer', function (): void {
    $customer = Customer::factory()->create();

    expect($customer->uuid)->toBeString();
});

it('should use uuid as getRouteKeyName', function (): void {
    $customer = Customer::factory()->create();
    expect($customer->getRouteKeyName())->toBe('uuid');
});
