<?php

namespace Database\Seeders;

use App\Models\Subscription;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class SubscriptionSeeder extends Seeder
{
    public function run(): void
    {
        $demo = User::where('email', 'demo@gym.com')->first();
        $andrii = User::where('email', 'andrii@gym.com')->first();

        if ($demo) {
            Subscription::create([
                'user_id'        => $demo->id,
                'plan_name'      => 'Standard',
                'price'          => 900.00,
                'sessions_total' => 16,
                'sessions_used'  => 3,
                'starts_at'      => Carbon::now()->startOfMonth()->toDateString(),
                'expires_at'     => Carbon::now()->endOfMonth()->toDateString(),
                'status'         => 'active',
            ]);
        }

        if ($andrii) {
            Subscription::create([
                'user_id'        => $andrii->id,
                'plan_name'      => 'Basic',
                'price'          => 500.00,
                'sessions_total' => 8,
                'sessions_used'  => 8,
                'starts_at'      => Carbon::now()->subMonth()->startOfMonth()->toDateString(),
                'expires_at'     => Carbon::now()->subMonth()->endOfMonth()->toDateString(),
                'status'         => 'expired',
            ]);
        }
    }
}
