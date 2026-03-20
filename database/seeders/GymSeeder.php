<?php

namespace Database\Seeders;

use App\Models\Gym;
use Illuminate\Database\Seeder;

class GymSeeder extends Seeder
{
    public function run(): void
    {
        $gyms = [
            [
                'name'           => 'FitLife Kyiv Central',
                'city'           => 'Kyiv',
                'address'        => 'Khreshchatyk St, 22',
                'max_capacity'   => 100,
                'current_people' => 45,
            ],
            [
                'name'           => 'PowerZone Lviv',
                'city'           => 'Lviv',
                'address'        => 'Svobody Ave, 7',
                'max_capacity'   => 80,
                'current_people' => 20,
            ],
            [
                'name'           => 'Harmony Studio Odesa',
                'city'           => 'Odesa',
                'address'        => 'Derybasivska St, 15',
                'max_capacity'   => 60,
                'current_people' => 5,
            ],
        ];

        foreach ($gyms as $gym) {
            Gym::create($gym);
        }
    }
}
