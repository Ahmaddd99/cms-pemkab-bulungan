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
        User::create([
            'name' => 'Admin',
            'username' => 'Admin',
            'password' => Hash::make('Bulungan2023')
        ]
        ,
        [
            'name' => 'Admin',
            'username' => 'api.v1',
            'password' => Hash::make('api.Bulungan2023')
        ]);
    }
}
