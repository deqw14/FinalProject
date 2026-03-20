<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\VisitHistoryResource;
use App\Models\Gym;
use App\Models\VisitHistory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CheckInController extends Controller
{
    public function checkIn(Request $request, Gym $gym): JsonResponse
    {
        $user = $request->user();

        $activeVisit = VisitHistory::where('user_id', $user->id)
            ->whereNull('checked_out_at')
            ->first();

        if ($activeVisit !== null) {
            return response()->json([
                'message' => 'You are already checked in to a gym. Please check out first.',
                'data'    => new VisitHistoryResource($activeVisit->load('gym')),
            ], 422);
        }

        $gym->increment('current_people');

        $visit = VisitHistory::create([
            'user_id'       => $user->id,
            'gym_id'        => $gym->id,
            'checked_in_at' => now(),
        ]);

        return response()->json([
            'message' => "Checked in to {$gym->name} successfully.",
            'data'    => new VisitHistoryResource($visit->load('gym')),
        ], 201);
    }

    public function checkOut(Request $request, Gym $gym): JsonResponse
    {
        $user = $request->user();

        $activeVisit = VisitHistory::where('user_id', $user->id)
            ->where('gym_id', $gym->id)
            ->whereNull('checked_out_at')
            ->first();

        if ($activeVisit === null) {
            return response()->json([
                'message' => 'You are not currently checked in to this gym.',
            ], 422);
        }

        $activeVisit->update([
            'checked_out_at' => now(),
        ]);

        if ($gym->current_people > 0) {
            $gym->decrement('current_people');
        }

        $activeVisit->load('gym');

        return response()->json([
            'message'          => "Checked out from {$gym->name} successfully.",
            'data'             => new VisitHistoryResource($activeVisit),
            'duration_minutes' => $activeVisit->duration_minutes,
        ]);
    }

    public function current(Request $request): JsonResponse
    {
        $activeVisit = VisitHistory::where('user_id', $request->user()->id)
            ->whereNull('checked_out_at')
            ->with('gym')
            ->first();

        return response()->json([
            'data' => $activeVisit ? new VisitHistoryResource($activeVisit) : null,
            'is_checked_in' => $activeVisit !== null,
        ]);
    }
}