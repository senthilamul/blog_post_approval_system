<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RejectPostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'reason' => 'required|string|min:10',
        ];
    }

    public function messages(): array
    {
        return [
            'reason.required' => 'Rejection reason is required.',
            'reason.min' => 'Rejection reason must be at least 10 characters.',
        ];
    }
}
