<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Websitesetup;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::factory()->create([

            "name"=> "Admin User",
            "email"=> "admin@gmail.com",
            "password"=> Hash::make("12345678"),

        ]);

       
        $role = new Role();
        $role->name = 'Super Admin';
        $role->save();

        $user->assignRole($role);



        $permissions = [
            // System Settings
            'dashboard',
            'admin-settings',
            'profile-settings',
            'security-settings',
            'notification-settings',
            'category-create',
            
            // Role Permission
            'role.create',
            'permission.create',
            'user.create',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
        $role->syncPermissions($permissions);
    }
}
