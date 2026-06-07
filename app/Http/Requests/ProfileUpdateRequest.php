<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Authorize request
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules
     */
    public function rules(): array
    {
        return [
            // ===================
            // NAME
            // ===================
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            // ===================
            // EMAIL
            // ===================
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],

            // ===================
            // PHOTO UPLOAD
            // ===================
            'photo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ];
    }

    /**
     * Custom validation messages
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Name is required.',
            'name.max' => 'Name cannot exceed 255 characters.',

            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already taken.',
            'email.lowercase' => 'Email must be lowercase.',

            'photo.image' => 'Profile photo must be an image.',
            'photo.mimes' => 'Allowed formats: JPG, JPEG, PNG, WEBP.',
            'photo.max' => 'Photo must not exceed 2MB.',
        ];
    }

    /**
     * Friendly attribute names
     */
    public function attributes(): array
    {
        return [
            'name' => 'Full Name',
            'email' => 'Email Address',
            'photo' => 'Profile Photo',
        ];
    }
}
