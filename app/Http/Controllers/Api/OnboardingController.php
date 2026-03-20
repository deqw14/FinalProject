<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OnboardingRequest;
use App\Http\Requests\UpdateGoalRequest;
use App\Http\Resources\TrainerResource;
use App\Models\OnboardingAnswer;
use App\Models\Trainer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OnboardingController extends Controller
{

    public function store(OnboardingRequest $request): JsonResponse
    {
        $user = $request->user();

        $answers = OnboardingAnswer::updateOrCreate(
            ['user_id' => $user->id],
            [
                'goal'               => $request->goal,
                'experience'         => $request->experience,
                'frequency_per_week' => $request->frequency_per_week,
                'trainer_style'      => $request->trainer_style,
            ]
        );

        $user->update(['onboarding_completed' => true]);

        return response()->json([
            'message' => 'Onboarding completed successfully.',
            'data'    => [
                'goal'               => $answers->goal,
                'experience'         => $answers->experience,
                'frequency_per_week' => $answers->frequency_per_week,
                'trainer_style'      => $answers->trainer_style,
            ],
        ]);
    }

   
    public function skip(Request $request): JsonResponse
    {
        $request->user()->update(['onboarding_completed' => true]);

        return response()->json([
            'message' => 'Onboarding skipped.',
        ]);
    }

   
    public function updateGoal(UpdateGoalRequest $request): JsonResponse
    {
        $user = $request->user();

        OnboardingAnswer::updateOrCreate(
            ['user_id' => $user->id],
            ['goal' => $request->goal]
        );

        return response()->json([
            'message' => 'Goal updated successfully.',
            'goal'    => $request->goal,
        ]);
    }

   
    public function match(Request $request): JsonResponse
    {
        $user = $request->user();
        $answers = $user->onboardingAnswers;

        if ($answers === null) {
            return response()->json([
                'message' => 'Please complete onboarding first.',
            ], 422);
        }

        $trainers = Trainer::with(['skills', 'gym', 'reviews'])
            ->whereHas('skills', function ($query) use ($answers) {
                $query->where('skill', $answers->goal);
            })
            ->withCount([
                'skills as match_score' => function ($query) use ($answers) {
                    $query->where('skill', $answers->goal);
                },
            ])
            ->orderByDesc('match_score')
            ->orderByDesc('rating')
            ->limit(2)
            ->get();

        if ($trainers->isEmpty()) {
            $trainers = Trainer::with(['skills', 'gym', 'reviews'])
                ->orderByDesc('rating')
                ->limit(2)
                ->get();
        }

        return response()->json([
            'data'    => TrainerResource::collection($trainers),
            'matched_goal' => $answers->goal,
        ]);
    }
}
