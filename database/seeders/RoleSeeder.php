<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $accountant_role = Role::findOrCreate('accountant');
        $user_role = Role::findOrCreate('user');

        $submit_tax_filing_permission = Permission::findOrCreate('submit-tax-filing');

        $user_role->permissions()->sync([$submit_tax_filing_permission->id]);
    }
}
