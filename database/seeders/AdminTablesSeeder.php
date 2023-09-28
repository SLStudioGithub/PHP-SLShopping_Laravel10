<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class AdminTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // create a role.
        Role::truncate();
        Role::create([
            'name' => 'Administrator',
            'slug' => 'administrator',
        ]);

        //create a permission
        Permission::truncate();
        Permission::insert([
            [
                'name'        => 'All permission',
                'slug'        => '*',
                'http_method' => '',
                'http_path'   => '*',
            ],
        ]);

        Admin::truncate();
        for ($i = 1; $i <= 100; $i++) {
            if ($i === 1) {
                Admin::create([
                    'email' => 'admin@example.com',
                    'password' => bcrypt('admin'),
                    'name'     => 'Administrator',
                ]);
            } else {
                Admin::create([
                    'email' => "admin{$i}@example.com",
                    'password' => bcrypt("admin{$i}"),
                    'name'     => "Administrator{$i}",
                ]);
            }
            // add role to user.
            Admin::find($i)->roles()->save(Role::first());
            // add permission to user.
            Admin::find($i)->permissions()->save(Permission::first());
        }
    }
}
