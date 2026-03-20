<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\GymController;
use App\Http\Controllers\Api\HistoryController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\OnboardingController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\SubscriptionController;
use App\Http\Controllers\Api\TrainerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CheckInController;



Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});


Route::get('/gyms', [GymController::class, 'index']);
Route::get('/gyms/{gym}', [GymController::class, 'show']);
Route::get('/gyms/{gym}/capacity', [GymController::class, 'capacity']);


Route::get('/trainers', [TrainerController::class, 'index']);
Route::get('/trainers/{trainer}', [TrainerController::class, 'show']);
Route::get('/trainers/{trainer}/slots', [TrainerController::class, 'slots']);


Route::get('/trainers/{trainer}/reviews', [ReviewController::class, 'trainerReviews']);


Route::get('/subscriptions/plans', [SubscriptionController::class, 'plans']);



Route::middleware('auth:sanctum')->group(function () {


    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);


    Route::post('/onboarding/answers', [OnboardingController::class, 'store']);
    Route::post('/onboarding/skip', [OnboardingController::class, 'skip']);
    Route::put('/onboarding/goal', [OnboardingController::class, 'updateGoal']);
    Route::get('/onboarding/match', [OnboardingController::class, 'match']);


    Route::get('/bookings', [BookingController::class, 'index']);
    Route::post('/bookings', [BookingController::class, 'store']);
    Route::get('/bookings/{booking}', [BookingController::class, 'show']);
    Route::delete('/bookings/{booking}', [BookingController::class, 'destroy']);


    Route::get('/subscriptions', [SubscriptionController::class, 'index']);
    Route::post('/subscriptions', [SubscriptionController::class, 'store']);
    Route::get('/balance', [SubscriptionController::class, 'balance']);


    Route::post('/reviews', [ReviewController::class, 'store']);


    Route::get('/history/visits', [HistoryController::class, 'visits']);
    Route::get('/history/trainings', [HistoryController::class, 'trainings']);

	Route::post('/gyms/{gym}/checkin', [CheckInController::class, 'checkIn']);
	Route::post('/gyms/{gym}/checkout', [CheckInController::class, 'checkOut']);
	Route::get('/checkin/current', [CheckInController::class, 'current']);
    
    
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead']);
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead']);
});
