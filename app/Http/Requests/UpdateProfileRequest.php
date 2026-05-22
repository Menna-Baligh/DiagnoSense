<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function rules(): array
    {
        $userId = auth()->id();

        return [
            'contact' => [
                'sometimes',
                'email',
                'unique:users,contact,'.$userId,
            ],
        ];
    }
}
