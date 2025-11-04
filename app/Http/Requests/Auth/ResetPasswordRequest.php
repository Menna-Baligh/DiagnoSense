<?php

namespace App\Http\Requests\Auth;

use App\Http\Responses\ApiResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ResetPasswordRequest extends FormRequest
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
            'password' => 'required|string|min:8|confirmed',
        ];
    }
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            ApiResponse::error('This action could not be completed due to validation errors.',
            $validator->errors(),
            422));
    }
}
