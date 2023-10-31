<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create(
            [
                'id' => 1,
                'name' => 'Admin',
                'username' => 'Admin',
                'password' => Hash::make('Bulungan2023')
            ],
            [
                'id' => 2,
                'name' => 'AdminAPI',
                'username' => 'api.v1',
                'password' => Hash::make('api.Bulungan2023')
            ]
        );
    }
}
