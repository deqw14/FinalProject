<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateGoalRequest extends FormRequest
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
        ];
    }
}
