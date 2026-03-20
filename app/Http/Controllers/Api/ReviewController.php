<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReviewRequest;
use App\Http\Resources\ReviewResource;
use App\Models\Booking;
use App\Models\Review;
use App\Models\Trainer;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{

    public function trainerReviews(Trainer $trainer): JsonResponse
    {
        $reviews = $trainer->reviews()
            ->with('user')
            ->orderByDesc('created_at')
            ->paginate(15);

        return response()->json([
            'data'  => ReviewResource::collection($reviews->items()),
            'meta'  => [
                'total'        => $reviews->total(),
                'per_page'     => $reviews->perPage(),
                'current_page' => $reviews->currentPage(),
                'last_page'    => $reviews->lastPage(),
            ],
            'trainer_rating' => (float) $trainer->rating,
        ]);
    }

    public function store(StoreReviewRequest $request): JsonResponse
    {
        $user    = $request->user();
        $booking = Booking::with('trainer')->findOrFail($request->booking_id);


        if ($booking->user_id !== $user->id) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        if ($booking->status !== 'completed') {
            return response()->json([
                'message' => 'You can only review a completed training session.',
            ], 422);
        }

        if ($booking->review !== null) {
            return response()->json([
                'message' => 'You have already reviewed this booking.',
            ], 422);
        }

        $review = DB::transaction(function () use ($user, $booking, $request) {
            $review = Review::create([
                'user_id'    => $user->id,
                'trainer_id' => $booking->trainer_id,
                'booking_id' => $booking->id,
                'rating'     => $request->rating,
                'comment'    => $request->comment,
            ]);

            $booking->trainer->updateRating();

            return $review;
        });

        $review->load('user');

        return response()->json([
            'message' => 'Review submitted successfully.',
            'data'    => new ReviewResource($review),
        ], 201);
    }
}
