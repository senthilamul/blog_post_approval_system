<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'status' => 'nullable|string|in:pending,approved,rejected',
            'approved_by' => 'nullable|integer|exists:users,id',
            'rejected_reason' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Post title is required.',
            'title.max' => 'Post title cannot exceed 255 characters.',
            'body.required' => 'Post content is required.',
            'status.in' => 'Status must be either pending, approved, or rejected.',
            'approved_by.exists' => 'The specified approver user does not exist.',
            'rejected_reason.max' => 'Rejection reason cannot exceed 1000 characters.',
        ];
    }
}
