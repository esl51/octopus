<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = ['root', 'admin', 'editor'];
        $permissions = [
            'manage access' => [],
            'manage users' => ['admin'],
            'manage media' => ['editor', 'admin'],
            'manage directories' => ['admin'],
            'manage statuses' => ['admin'],
            'manage pages' => ['editor'],
        ];

        foreach ($roles as $role) {
            Role::create(['name' => $role]);
        }

        foreach ($permissions as $item => $roles) {
            $permission = Permission::create(['name' => $item]);
            foreach ($roles as $roleItem) {
                $role = Role::where(['name' => $roleItem])->first();
                $role->givePermissionTo($item);
            }
        }
    }
}
