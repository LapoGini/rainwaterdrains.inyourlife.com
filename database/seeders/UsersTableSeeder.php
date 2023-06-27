<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $operatoreRole = config('roles.models.role')::where('name', '=', 'Operatore')->first();
        $clienteRole = config('roles.models.role')::where('name', '=', 'Cliente')->first();
        $adminRole = config('roles.models.role')::where('name', '=', 'Admin')->first();
        $permissions = config('roles.models.permission')::all();

        /*
         * Add Users
         *
         */
        if (config('roles.models.defaultUser')::where('email', '=', 'admin@admin.com')->first() === null) {
            $newUser = config('roles.models.defaultUser')::create([
                'name'     => 'Admin',
                'email'    => 'admin@admin.com',
                'password' => bcrypt('password'),
                'api_token' => Str::random(80),
            ]);

            $newUser->attachRole($adminRole);
            foreach ($permissions as $permission) {
                $newUser->attachPermission($permission);
            }
        }

        if (config('roles.models.defaultUser')::where('email', '=', 'user@user.com')->first() === null) {
            $newUser = config('roles.models.defaultUser')::create([
                'name'     => 'User',
                'email'    => 'user@user.com',
                'password' => bcrypt('password'),
                'api_token' => Str::random(80),
            ]);

            $newUser->attachRole($operatoreRole);
        }

        if (config('roles.models.defaultUser')::where('email', '=', 'cliente@cliente.com')->first() === null) {
            $newUser = config('roles.models.defaultUser')::create([
                'name'     => 'Cliente',
                'email'    => 'cliente@cliente.com',
                'password' => bcrypt('password'),
                'api_token' => Str::random(80),
            ]);

            $newUser->attachRole($clienteRole);
        }
    }
}
