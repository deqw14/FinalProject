<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookingResource;
use App\Http\Resources\VisitHistoryResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HistoryController extends Controller
{

    public function visits(Request $request): JsonResponse
    {
        $visits = $request->user()
            ->visitHistory()
            ->with(['gym', 'booking.trainer'])
            ->orderByDesc('checked_in_at')
            ->paginate(20);

        return response()->json([
            'data' => VisitHistoryResource::collection($visits->items()),
            'meta' => [
                'total'        => $visits->total(),
                'per_page'     => $visits->perPage(),
                'current_page' => $visits->currentPage(),
                'last_page'    => $visits->lastPage(),
            ],
        ]);
    }


    public function trainings(Request $request): JsonResponse
    {
        $trainings = $request->user()
            ->bookings()
            ->with(['trainer.gym', 'review'])
            ->where('status', 'completed')
            ->orderByDesc('booking_date')
            ->paginate(20);

        return response()->json([
            'data' => BookingResource::collection($trainings->items()),
            'meta' => [
                'total'        => $trainings->total(),
                'per_page'     => $trainings->perPage(),
                'current_page' => $trainings->currentPage(),
                'last_page'    => $trainings->lastPage(),
            ],
        ]);
    }
}
