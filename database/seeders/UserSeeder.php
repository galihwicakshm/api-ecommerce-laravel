<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create(
            [
                'name' => 'Galih Wicaksono',
                'email' => 'galih@gmail.com',
                'role' => 'admin',
                'password' => Hash::make('galih123'),
            ],

        );

        User::create(
            [
                'name' => 'Hanggoro Mukti',
                'email' => 'galih1@gmail.com',
                'role' => 'role',
                'password' => Hash::make('galih123'),
            ],

        );
    }
}
