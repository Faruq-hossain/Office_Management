<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()

    // role create korlam
    {
        $role = Role::create(['name' => 'admin']);

        //jotogula permission thakbe ta seeder er maddhome dia dilam

        $permissions = [
            ['name' => 'user list'],
            ['name' => 'create user'],
            ['name' => 'edit user'],
            ['name' => 'delete user'],
            ['name' => 'role list'],
            ['name' => 'create role'],
            ['name' => 'edit role'],
            ['name' => 'delete role'],
        ];

        //permission create kore nilam

        foreach ($permissions as $item) {
            Permission::create($item);
        }

        // permission assign korlam 

        $role->syncPermissions(Permission::all());

        $user = User::first();
        //user er jonno assige korlam
        $user->assignRole($role);
    }
}
