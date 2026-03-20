<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PurchaseSubscriptionRequest;
use App\Http\Resources\SubscriptionResource;
use App\Models\Notification;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class SubscriptionController extends Controller
{
  
    private const PLANS = [
        'basic' => [
            'name'           => 'Basic',
            'price'          => 500.00,
            'sessions_total' => 8,
            'days'           => 30,
        ],
        'standard' => [
            'name'           => 'Standard',
            'price'          => 900.00,
            'sessions_total' => 16,
            'days'           => 30,
        ],
        'premium' => [
            'name'           => 'Premium',
            'price'          => 1500.00,
            'sessions_total' => 30,
            'days'           => 30,
        ],
    ];


    public function plans(): JsonResponse
    {
        return response()->json([
            'data' => array_values(array_map(
                fn ($key, $plan) => array_merge(['key' => $key], $plan),
                array_keys(self::PLANS),
                self::PLANS
            )),
        ]);
    }


    public function index(Request $request): JsonResponse
    {
        $subscriptions = $request->user()
            ->subscriptions()
            ->orderByDesc('created_at')
            ->get();

        return response()->json([
            'data' => SubscriptionResource::collection($subscriptions),
        ]);
    }


    public function store(PurchaseSubscriptionRequest $request): JsonResponse
    {
        $user = $request->user();
        $plan = self::PLANS[$request->plan];

        $subscription = DB::transaction(function () use ($user, $plan) {

            $user->increment('balance', $plan['price']);

            $now = Carbon::now();

            $subscription = Subscription::create([
                'user_id'        => $user->id,
                'plan_name'      => $plan['name'],
                'price'          => $plan['price'],
                'sessions_total' => $plan['sessions_total'],
                'sessions_used'  => 0,
                'starts_at'      => $now->toDateString(),
                'expires_at'     => $now->addDays($plan['days'])->toDateString(),
                'status'         => 'active',
            ]);

            Notification::create([
                'user_id' => $user->id,
                'title'   => 'Subscription activated',
                'body'    => "Your {$plan['name']} plan has been activated. You have {$plan['sessions_total']} sessions available.",
                'type'    => 'general',
            ]);

            return $subscription;
        });

        return response()->json([
            'message' => 'Subscription purchased successfully.',
            'data'    => new SubscriptionResource($subscription),
        ], 201);
    }

    public function balance(Request $request): JsonResponse
    {
        $user    = $request->user();
        $balance = (float) $user->balance;

        $sessionCost      = 200.00;
        $hasSufficientFunds = $balance >= $sessionCost;

        $activeSubscription = $user->activeSubscription;

        return response()->json([
            'data' => [
                'balance'              => $balance,
                'has_sufficient_funds' => $hasSufficientFunds,
                'session_cost'         => $sessionCost,
                'message'              => $hasSufficientFunds
                    ? 'Your balance is sufficient for a training session.'
                    : 'Your balance is too low. Please purchase a subscription.',
                'active_subscription'  => $activeSubscription
                    ? new SubscriptionResource($activeSubscription)
                    : null,
            ],
        ]);
    }
}
