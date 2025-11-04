<?php

namespace App\Http\Requests\Auth;

use App\Http\Responses\ApiResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class EmailVerificationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|max:6',
        ];
    }
    public function messages(): array
    {
        return [
            'email.required' => 'Email is required.',
            'email.email' => 'Please provide a valid email address.',
            'email.exists' => 'No account found with this email.',
            'otp.required' => 'OTP is required.',
            'otp.max' => 'OTP must not exceed 6 characters.',
        ];
    }
    public function failedValidation(Validator $validator){
        throw new HttpResponseException(
            ApiResponse::error('This action could not be completed due to validation errors.',
            $validator->errors(),
            422));
    }
}
