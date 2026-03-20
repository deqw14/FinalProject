<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookingRequest;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Models\Notification;
use App\Models\TimeSlot;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{

    public function index(Request $request): JsonResponse
    {
        $bookings = $request->user()
            ->bookings()
            ->with(['trainer.gym', 'review'])
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->orderByDesc('booking_date')
            ->get();

        return response()->json([
            'data' => BookingResource::collection($bookings),
        ]);
    }

    public function store(StoreBookingRequest $request): JsonResponse
    {
        $user = $request->user();
        $slot = TimeSlot::with('trainer')->findOrFail($request->time_slot_id);
        $date = Carbon::parse($request->booking_date);

        if ($slot->day_of_week !== $date->isoWeekday()) {
            return response()->json([
                'message' => 'This slot is not available on the selected date.',
            ], 422);
        }

        $existingCount = Booking::where('time_slot_id', $slot->id)
            ->where('booking_date', $date->toDateString())
            ->where('status', '!=', 'cancelled')
            ->count();

        if ($existingCount >= $slot->max_participants) {
            return response()->json([
                'message' => 'This time slot is fully booked.',
            ], 422);
        }

        $duplicate = Booking::where('user_id', $user->id)
            ->where('time_slot_id', $slot->id)
            ->where('booking_date', $date->toDateString())
            ->where('status', '!=', 'cancelled')
            ->exists();

        if ($duplicate) {
            return response()->json([
                'message' => 'You already have a booking for this slot on this date.',
            ], 422);
        }

        $sessionPrice = 200.00;

        if ((float) $user->balance < $sessionPrice) {
            return response()->json([
                'message' => 'Insufficient balance. Please top up your account.',
                'balance' => (float) $user->balance,
                'required' => $sessionPrice,
            ], 422);
        }

        $booking = DB::transaction(function () use ($user, $slot, $date, $sessionPrice) {
            $user->decrement('balance', $sessionPrice);

            $booking = Booking::create([
                'user_id'          => $user->id,
                'trainer_id'       => $slot->trainer_id,
                'time_slot_id'     => $slot->id,
                'booking_date'     => $date->toDateString(),
                'start_time'       => $slot->start_time,
                'end_time'         => $slot->end_time,
                'duration_minutes' => $slot->duration_minutes,
                'price'            => $sessionPrice,
                'status'           => 'confirmed',
            ]);

            Notification::create([
                'user_id' => $user->id,
                'title'   => 'Training reminder',
                'body'    => "You have a training session with {$slot->trainer->name} tomorrow at {$slot->start_time}.",
                'type'    => 'booking_reminder',
            ]);

            return $booking;
        });

        $booking->load(['trainer.gym', 'review']);

        return response()->json([
            'message' => 'Booking confirmed.',
            'data'    => new BookingResource($booking),
        ], 201);
    }

    public function show(Request $request, Booking $booking): JsonResponse
    {
        if ($booking->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        $booking->load(['trainer.gym', 'review']);

        return response()->json([
            'data' => new BookingResource($booking),
        ]);
    }

    public function destroy(Request $request, Booking $booking): JsonResponse
    {
        if ($booking->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        if (! $booking->canBeCancelled()) {
            return response()->json([
                'message' => 'Cannot cancel a booking less than 1 hour before the start time or if it is already cancelled.',
            ], 422);
        }

        DB::transaction(function () use ($booking, $request) {
            $booking->update([
                'status'       => 'cancelled',
                'cancelled_at' => now(),
            ]);

            $request->user()->increment('balance', $booking->price);

            Notification::create([
                'user_id' => $request->user()->id,
                'title'   => 'Booking cancelled',
                'body'    => "Your booking on {$booking->booking_date->toDateString()} at {$booking->start_time} has been cancelled. {$booking->price} UAH has been refunded to your balance.",
                'type'    => 'booking_cancelled',
            ]);
        });

        return response()->json([
            'message'  => 'Booking cancelled. Amount refunded to your balance.',
            'refunded' => (float) $booking->price,
        ]);
    }
}
