<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\TeamRole;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = Role::firstOrCreate(['name' => TeamRole::ADMIN]);

        $role->givePermissionTo(Permission::firstOrCreate(['name' => 'update team']));
        $role->givePermissionTo(Permission::firstOrCreate(['name' => 'view team members']));
        $role->givePermissionTo(Permission::firstOrCreate(['name' => 'remove team members']));
        $role->givePermissionTo(Permission::firstOrCreate(['name' => 'invite to team']));
        $role->givePermissionTo(Permission::firstOrCreate(['name' => 'revoke invitation']));
        $role->givePermissionTo(Permission::firstOrCreate(['name' => 'change member roles']));
    }
}
