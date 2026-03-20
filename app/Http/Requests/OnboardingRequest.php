<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OnboardingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'goal' => [
                'required',
                Rule::in([
                    'weight_loss',
                    'muscle_gain',
                    'crossfit',
                    'yoga',
                    'pilates',
                    'rehabilitation',
                ]),
            ],
            'experience' => [
                'required',
                Rule::in(['beginner', 'intermediate', 'advanced']),
            ],
            'frequency_per_week' => ['required', 'integer', 'min:1', 'max:7'],
            'trainer_style' => [
                'required',
                Rule::in(['strict', 'friendly', 'motivational', 'calm']),
            ],
        ];
    }
}
