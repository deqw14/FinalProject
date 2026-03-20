<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\GymResource;
use App\Models\Gym;
use Illuminate\Http\JsonResponse;

class GymController extends Controller
{

    public function index(): JsonResponse
    {
        $gyms = Gym::all();

        return response()->json([
            'data' => GymResource::collection($gyms),
        ]);
    }


    public function show(Gym $gym): JsonResponse
    {
        return response()->json([
            'data' => new GymResource($gym),
        ]);
    }


    public function capacity(Gym $gym): JsonResponse
    {
        return response()->json([
            'data' => [
                'gym_id'               => $gym->id,
                'gym_name'             => $gym->name,
                'current_people'       => $gym->current_people,
                'max_capacity'         => $gym->max_capacity,
                'occupancy_percentage' => $gym->occupancy_percentage_attribute,
            ],
        ]);
    }
}
