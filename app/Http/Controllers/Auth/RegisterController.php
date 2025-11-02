<?php

namespace App\Http\Controllers\Auth;

use Hash;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegistrationRequest;

class RegisterController extends Controller
{
    public function registerDoctor(RegistrationRequest $request)
    {
        return $this->register($request, 'doctor');
    }
    public function registerPatient(RegistrationRequest $request)
    {
        return $this->register($request, 'patient');
    }
    protected function register(RegistrationRequest $request, string $role)
    {
        $validated = $request->validated();
        $validated['type'] = $role;
        $validated['password'] = Hash::make($validated['password']);
        $user = User::create($validated);
        if($role === 'patient'){
            $user->patient()->create();
        } elseif($role === 'doctor'){
            $user->doctor()->create();
        }
        $token = $user->createToken('register-token')->plainTextToken ;
        return response()->json([
            'status' => true,
            'message' => 'User registered successfully.',
            'data' => [
                'user' => $user ,
                'token' => $token
            ]
        ], 201);
    }
}
