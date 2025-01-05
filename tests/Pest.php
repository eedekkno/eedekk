<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind a different classes or traits.
|
*/

use Database\Seeders\AdminRoleSeeder;
use Database\Seeders\MemberRoleSeeder;

pest()->extend(Tests\TestCase::class)
    ->use(Illuminate\Foundation\Testing\RefreshDatabase::class)
    ->in('Feature')
    ->beforeEach(function (): void {
        $this->seed([
            AdminRoleSeeder::class,
            MemberRoleSeeder::class,
        ]);
    });
