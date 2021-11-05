<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use \App\Models\User;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@qq.com',
            'password' => Hash::make('password'),
            'role' => User::ROLE_ADMIN,
            'status' =>  User::STATUS_ACTIVE,
        ]);

        \App\Models\User::factory(10)->create();
    }
}
