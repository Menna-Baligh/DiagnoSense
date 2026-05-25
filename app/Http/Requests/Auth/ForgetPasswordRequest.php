<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use App\Rules\UserData\ValidContactRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ForgetPasswordRequest extends FormRequest
{
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
        ];
    }

    public function messages(): array
    {
        return [
            'contact.required' => 'Please enter your email or phone number.',
            'contact.exists'   => 'This contact is invalid.',
        ];
    }
}
