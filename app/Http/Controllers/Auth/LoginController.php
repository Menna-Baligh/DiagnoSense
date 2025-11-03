<?php

namespace App\Http\Controllers\Auth;

use App\Http\Responses\ApiResponse;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Auth\LoginRequest;

class LoginController extends Controller
{
    public function loginDoctor(LoginRequest $request)
    {
        return $this->login($request, 'doctor');
    }

    public function loginPatient(LoginRequest $request)
    {
        return $this->login($request, 'patient');
    }
    public function login(LoginRequest $request, string $type)
    {
        $validated = $request->validated();

        $user = User::where('email', $validated['email'])
                    ->where('type', $type)
                    ->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return ApiResponse::error('Bad credentials provided.', null, 401);
        }

        $token = $user->createToken('login-token')->plainTextToken;
        $user = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'updated_at' => $user->updated_at->format('Y-m-d h:i:s'),
            'created_at' => $user->created_at->format('Y-m-d h:i:s'),
            ];
        $data = [
            'user' => $user,
            'token' => $token,
        ];
        return ApiResponse::success('Login successfully.', $data , 200);

    }
}
