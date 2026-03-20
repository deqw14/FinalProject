<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('onboarding_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('goal', [
                'weight_loss',
                'muscle_gain',
                'crossfit',
                'yoga',
                'pilates',
                'rehabilitation',
            ]);
            $table->enum('experience', ['beginner', 'intermediate', 'advanced']);
            $table->unsignedTinyInteger('frequency_per_week'); 
            $table->enum('trainer_style', ['strict', 'friendly', 'motivational', 'calm']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('onboarding_answers');
    }
};
