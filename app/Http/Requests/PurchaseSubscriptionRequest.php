<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PurchaseSubscriptionRequest extends FormRequest
{
    public const VALID_CARD = '4111111111111111';

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'plan'        => ['required', Rule::in(['basic', 'standard', 'premium'])],
            'card_number' => ['required', 'string', 'size:16'],
        ];
    }

    public function withValidator(\Illuminate\Contracts\Validation\Validator $validator): void
    {
        $validator->after(function (\Illuminate\Contracts\Validation\Validator $v) {
            if ($this->input('card_number') !== self::VALID_CARD) {
                $v->errors()->add('card_number', 'Card number is invalid or declined.');
            }
        });
    }
}
