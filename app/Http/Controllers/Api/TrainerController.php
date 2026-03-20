<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TimeSlotResource;
use App\Http\Resources\TrainerResource;
use App\Models\Booking;
use App\Models\Trainer;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TrainerController extends Controller
{

    public function index(Request $request): JsonResponse
    {
        $query = Trainer::with(['skills', 'gym', 'reviews']);

        if ($request->filled('skill')) {
            $query->whereHas('skills', function ($q) use ($request) {
                $q->where('skill', $request->skill);
            });
        }

        if ($request->filled('gym_id')) {
            $query->where('gym_id', $request->gym_id);
        }

        $trainers = $query->orderByDesc('rating')->get();

        return response()->json([
            'data' => TrainerResource::collection($trainers),
        ]);
    }


    public function show(Trainer $trainer): JsonResponse
    {
        $trainer->load(['skills', 'gym', 'reviews.user']);

        return response()->json([
            'data' => new TrainerResource($trainer),
        ]);
    }


    public function slots(Request $request, Trainer $trainer): JsonResponse
    {
        $request->validate([
            'date' => ['required', 'date', 'after_or_equal:today'],
        ]);

        $date = Carbon::parse($request->date);
        $dayOfWeek = $date->isoWeekday(); 

        $slots = $trainer->timeSlots()
            ->where('day_of_week', $dayOfWeek)
            ->where('is_active', true)
            ->get()
            ->map(function ($slot) use ($date) {
                $bookedCount = Booking::where('time_slot_id', $slot->id)
                    ->where('booking_date', $date->toDateString())
                    ->where('status', '!=', 'cancelled')
                    ->count();

                $available = $bookedCount < $slot->max_participants;

                return array_merge(
                    (new TimeSlotResource($slot))->toArray(request()),
                    ['available' => $available, 'booked_count' => $bookedCount]
                );
            });

        return response()->json([
            'data' => $slots,
            'date' => $date->toDateString(),
        ]);
    }
}
