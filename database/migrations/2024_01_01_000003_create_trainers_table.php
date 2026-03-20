<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trainers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gym_id')->constrained('gyms')->cascadeOnDelete();
            $table->string('name');
            $table->text('description');
            $table->string('photo')->nullable();
            $table->string('specialization');
            $table->decimal('rating', 3, 2)->default(0);
            $table->unsignedInteger('max_clients')->default(20);
            $table->timestamps();
        });

        Schema::create('trainer_skills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trainer_id')->constrained('trainers')->cascadeOnDelete();
            $table->enum('skill', [
                'weight_loss',
                'muscle_gain',
                'crossfit',
                'yoga',
                'pilates',
                'rehabilitation',
            ]);
            $table->unique(['trainer_id', 'skill']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trainer_skills');
        Schema::dropIfExists('trainers');
    }
};
