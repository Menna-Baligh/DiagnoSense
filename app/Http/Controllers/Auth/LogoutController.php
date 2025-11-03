<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use Illuminate\Http\Request ;

class LogoutController extends Controller
{
    public function logoutDoctor(Request $request)
    {
        return $this->logout($request, 'doctor');
    }

    public function logoutPatient(Request $request)
    {
        return $this->logout($request, 'patient');
    }
    protected function logout(Request $request ,string $type){
        $user = $request->user();
        if ($user->type !== $type) {
            return response()->json([
                'status' => false,
                'message' => "Unauthorized: user is not a {$type}.",
            ], 403);
        }
        $user->tokens()->delete();
        return ApiResponse::success('Logout successfully.', null, 200);
        
    }
}
