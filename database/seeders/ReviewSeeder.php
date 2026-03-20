<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Review;
use App\Models\Trainer;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $demo   = User::where('email', 'demo@gym.com')->first();
        $andrii = User::where('email', 'andrii@gym.com')->first();
        $olha   = User::where('email', 'olha@gym.com')->first();

        $trainer1 = Trainer::first();
        $trainer2 = Trainer::skip(1)->first();

        if (! $demo || ! $trainer1 || ! $trainer2) {
            return;
        }

        $booking1 = Booking::create([
            'user_id'          => $demo->id,
            'trainer_id'       => $trainer1->id,
            'time_slot_id'     => $trainer1->timeSlots()->first()?->id ?? 1,
            'booking_date'     => Carbon::now()->subDays(7)->toDateString(),
            'start_time'       => '08:00:00',
            'end_time'         => '09:00:00',
            'duration_minutes' => 60,
            'price'            => 200.00,
            'status'           => 'completed',
        ]);

        $booking2 = Booking::create([
            'user_id'          => $andrii ? $andrii->id : $demo->id,
            'trainer_id'       => $trainer1->id,
            'time_slot_id'     => $trainer1->timeSlots()->first()?->id ?? 1,
            'booking_date'     => Carbon::now()->subDays(14)->toDateString(),
            'start_time'       => '10:00:00',
            'end_time'         => '11:00:00',
            'duration_minutes' => 60,
            'price'            => 200.00,
            'status'           => 'completed',
        ]);

        $booking3 = Booking::create([
            'user_id'          => $olha ? $olha->id : $demo->id,
            'trainer_id'       => $trainer2->id,
            'time_slot_id'     => $trainer2->timeSlots()->first()?->id ?? 2,
            'booking_date'     => Carbon::now()->subDays(5)->toDateString(),
            'start_time'       => '07:00:00',
            'end_time'         => '08:00:00',
            'duration_minutes' => 60,
            'price'            => 200.00,
            'status'           => 'completed',
        ]);

        Review::create([
            'user_id'    => $demo->id,
            'trainer_id' => $trainer1->id,
            'booking_id' => $booking1->id,
            'rating'     => 5,
            'comment'    => 'Amazing trainer! Oleksiy really pushed me and I can already see results after just a few sessions.',
        ]);

        Review::create([
            'user_id'    => $andrii ? $andrii->id : $demo->id,
            'trainer_id' => $trainer1->id,
            'booking_id' => $booking2->id,
            'rating'     => 4,
            'comment'    => 'Very professional and knowledgeable. The workout was intense but well structured.',
        ]);

        Review::create([
            'user_id'    => $olha ? $olha->id : $demo->id,
            'trainer_id' => $trainer2->id,
            'booking_id' => $booking3->id,
            'rating'     => 5,
            'comment'    => 'Nadiia is incredibly patient and skilled. My flexibility improved so much already!',
        ]);

        $trainer1->updateRating();
        $trainer2->updateRating();
    }
}
