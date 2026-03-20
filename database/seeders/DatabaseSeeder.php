<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            GymSeeder::class,
            TrainerSeeder::class,
            UserSeeder::class,
            SubscriptionSeeder::class,
            ReviewSeeder::class,
        ]);
    }
}
