<?php

namespace Database\Seeders;

use App\Models\Trainer;
use App\Models\TrainerSkill;
use App\Models\TimeSlot;
use Illuminate\Database\Seeder;

class TrainerSeeder extends Seeder
{
    public function run(): void
    {
        $trainers = [
            [
                'gym_id'         => 1,
                'name'           => 'Oleksiy Marchenko',
                'description'    => 'Certified strength & conditioning coach with 8 years of experience. Specializes in body transformation and powerlifting.',
                'photo'          => 'https://i.pravatar.cc/300?img=11',
                'specialization' => 'Strength & Conditioning',
                'rating'         => 4.80,
                'max_clients'    => 15,
                'skills'         => ['weight_loss', 'muscle_gain', 'crossfit'],
                'slots'          => [
                    ['day_of_week' => 1, 'start_time' => '08:00', 'end_time' => '09:00'],
                    ['day_of_week' => 1, 'start_time' => '10:00', 'end_time' => '11:00'],
                    ['day_of_week' => 3, 'start_time' => '08:00', 'end_time' => '09:00'],
                    ['day_of_week' => 3, 'start_time' => '17:00', 'end_time' => '18:00'],
                    ['day_of_week' => 5, 'start_time' => '09:00', 'end_time' => '10:00'],
                ],
            ],
            [
                'gym_id'         => 1,
                'name'           => 'Nadiia Kovalenko',
                'description'    => 'Yoga and Pilates instructor. Helps clients find balance, flexibility, and inner peace. 6 years of practice.',
                'photo'          => 'https://i.pravatar.cc/300?img=23',
                'specialization' => 'Yoga & Pilates',
                'rating'         => 4.95,
                'max_clients'    => 10,
                'skills'         => ['yoga', 'pilates', 'rehabilitation'],
                'slots'          => [
                    ['day_of_week' => 2, 'start_time' => '07:00', 'end_time' => '08:00'],
                    ['day_of_week' => 2, 'start_time' => '18:00', 'end_time' => '19:00'],
                    ['day_of_week' => 4, 'start_time' => '07:00', 'end_time' => '08:00'],
                    ['day_of_week' => 6, 'start_time' => '10:00', 'end_time' => '11:00'],
                ],
            ],
            [
                'gym_id'         => 2,
                'name'           => 'Dmytro Savchenko',
                'description'    => 'CrossFit Level 2 trainer and competitive athlete. Pushes clients to their limits safely and effectively.',
                'photo'          => 'https://i.pravatar.cc/300?img=33',
                'specialization' => 'CrossFit',
                'rating'         => 4.70,
                'max_clients'    => 20,
                'skills'         => ['crossfit', 'weight_loss', 'muscle_gain'],
                'slots'          => [
                    ['day_of_week' => 1, 'start_time' => '06:00', 'end_time' => '07:00'],
                    ['day_of_week' => 3, 'start_time' => '06:00', 'end_time' => '07:00'],
                    ['day_of_week' => 5, 'start_time' => '06:00', 'end_time' => '07:00'],
                    ['day_of_week' => 6, 'start_time' => '09:00', 'end_time' => '10:00'],
                ],
            ],
            [
                'gym_id'         => 2,
                'name'           => 'Iryna Bondarenko',
                'description'    => 'Rehabilitation specialist and certified physiotherapist. Works with post-injury recovery and chronic pain management.',
                'photo'          => 'https://i.pravatar.cc/300?img=47',
                'specialization' => 'Rehabilitation',
                'rating'         => 4.90,
                'max_clients'    => 8,
                'skills'         => ['rehabilitation', 'yoga', 'pilates'],
                'slots'          => [
                    ['day_of_week' => 2, 'start_time' => '09:00', 'end_time' => '10:00'],
                    ['day_of_week' => 4, 'start_time' => '09:00', 'end_time' => '10:00'],
                    ['day_of_week' => 4, 'start_time' => '15:00', 'end_time' => '16:00'],
                ],
            ],
            [
                'gym_id'         => 3,
                'name'           => 'Vasyl Petrenko',
                'description'    => 'Weight loss and nutrition coach. Combines cardio, HIIT, and diet planning for maximum fat burning results.',
                'photo'          => 'https://i.pravatar.cc/300?img=55',
                'specialization' => 'Weight Loss & Nutrition',
                'rating'         => 4.60,
                'max_clients'    => 12,
                'skills'         => ['weight_loss', 'crossfit'],
                'slots'          => [
                    ['day_of_week' => 1, 'start_time' => '07:00', 'end_time' => '08:00'],
                    ['day_of_week' => 2, 'start_time' => '07:00', 'end_time' => '08:00'],
                    ['day_of_week' => 5, 'start_time' => '17:00', 'end_time' => '18:00'],
                    ['day_of_week' => 7, 'start_time' => '10:00', 'end_time' => '11:00'],
                ],
            ],
        ];

        foreach ($trainers as $trainerData) {
            $skills = $trainerData['skills'];
            $slots  = $trainerData['slots'];

            unset($trainerData['skills'], $trainerData['slots']);

            $trainer = Trainer::create($trainerData);

            foreach ($skills as $skill) {
                TrainerSkill::create([
                    'trainer_id' => $trainer->id,
                    'skill'      => $skill,
                ]);
            }

            foreach ($slots as $slot) {
                TimeSlot::create([
                    'trainer_id'       => $trainer->id,
                    'day_of_week'      => $slot['day_of_week'],
                    'start_time'       => $slot['start_time'],
                    'end_time'         => $slot['end_time'],
                    'max_participants' => 1,
                    'is_active'        => true,
                ]);
            }
        }
    }
}
