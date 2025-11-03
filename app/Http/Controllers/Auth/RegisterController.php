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
    protected function register(RegistrationRequest $request, string $type)
    {
        $validated = $request->validated();
        $validated['type'] = $type;
        $validated['password'] = Hash::make($validated['password']);
        $user = User::create($validated);
        if($type === 'patient'){
            $user->patient()->create();
        } elseif($type === 'doctor'){
            $user->doctor()->create();
        }
        $token = $user->createToken('register-token')->plainTextToken ;
        return response()->json([
            'success' => true,
            'message' => 'user registered successfully.',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'updated_at' => $user->updated_at->format('Y-m-d h:i:s'),
                    'created_at' => $user->created_at->format('Y-m-d h:i:s')
                ] ,
                'token' => $token
            ]
        ], 201);
    }
}
