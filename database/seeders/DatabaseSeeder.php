<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Station;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::create([
            'name'     => 'Admin',
            'username' => 'admin',
            'email'    => 'k.palconete.559180@umindanao.edu.ph',
            'password' => bcrypt('admin123'),
            'role'     => 'admin',
        ]);

        // Seed 20 PC Stations
        for ($i = 1; $i <= 20; $i++) {
            Station::create([
                'name'        => 'PC ' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'is_occupied' => false,
                'user_id'     => null,
            ]);
        }
    }
}