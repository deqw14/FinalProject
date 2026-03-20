<?php

namespace Database\Seeders;

use App\Models\OnboardingAnswer;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {

        $demo = User::create([
            'name'                 => 'Demo User',
            'email'                => 'demo@gym.com',
            'password'             => Hash::make('password'),
            'balance'              => 1000.00,
            'onboarding_completed' => true,
        ]);

        OnboardingAnswer::create([
            'user_id'            => $demo->id,
            'goal'               => 'weight_loss',
            'experience'         => 'beginner',
            'frequency_per_week' => 3,
            'trainer_style'      => 'motivational',
        ]);

        $users = [
            [
                'name'                 => 'Andrii Kovalchuk',
                'email'                => 'andrii@gym.com',
                'password'             => Hash::make('password'),
                'balance'              => 500.00,
                'onboarding_completed' => true,
                'goal'                 => 'muscle_gain',
                'experience'           => 'intermediate',
                'frequency'            => 4,
                'style'                => 'strict',
            ],
            [
                'name'                 => 'Olha Sydorenko',
                'email'                => 'olha@gym.com',
                'password'             => Hash::make('password'),
                'balance'              => 200.00,
                'onboarding_completed' => true,
                'goal'                 => 'yoga',
                'experience'           => 'beginner',
                'frequency'            => 2,
                'style'                => 'calm',
            ],
            [
                'name'                 => 'Mykola Hrytsenko',
                'email'                => 'mykola@gym.com',
                'password'             => Hash::make('password'),
                'balance'              => 0.00,
                'onboarding_completed' => false,
                'goal'                 => null,
                'experience'           => null,
                'frequency'            => null,
                'style'                => null,
            ],
        ];

        foreach ($users as $userData) {
            $goal       = $userData['goal'];
            $experience = $userData['experience'];
            $frequency  = $userData['frequency'];
            $style      = $userData['style'];

            unset($userData['goal'], $userData['experience'], $userData['frequency'], $userData['style']);

            $user = User::create($userData);

            if ($goal !== null) {
                OnboardingAnswer::create([
                    'user_id'            => $user->id,
                    'goal'               => $goal,
                    'experience'         => $experience,
                    'frequency_per_week' => $frequency,
                    'trainer_style'      => $style,
                ]);
            }
        }
    }
}
