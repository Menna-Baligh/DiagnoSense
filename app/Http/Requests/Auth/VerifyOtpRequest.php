<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use App\Rules\UserData\ValidContactRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VerifyOtpRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $type = $this->route('type');
        return [
            'contact' => [
                'required',
                'string',
                new ValidContactRule,
                Rule::exists('users', 'contact')->where(function ($query) use ($type) {
                    $query->where('type', $type);
                })
            ],
            'otp' => ['required', 'string', 'size:6'],
        ];
    }

    public function messages(): array
    {
        return [
            'contact.required' => 'Contact is required.',
            'contact.exists'   => 'This contact is invalid.',
        ];
    }
}
