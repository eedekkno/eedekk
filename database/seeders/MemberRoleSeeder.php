<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\TeamRole;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class MemberRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = Role::firstOrCreate(['name' => TeamRole::MEMBER]);
        $role->givePermissionTo(Permission::firstOrCreate(['name' => 'view team members']));
    }
}
