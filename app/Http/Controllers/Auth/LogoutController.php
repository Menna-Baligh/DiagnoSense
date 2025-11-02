<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
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
        try{
            $user = $request->user();
            if ($user->type !== $type) {
                return response()->json([
                    'status' => false,
                    'message' => "Unauthorized: user is not a {$type}.",
                ], 403);
            }
            $user->tokens()->delete();
            return response()->json([
                'success' => true,
                'message' => 'Logout successfully.',
            ], 200);
        }catch(\Exception $e){
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 401);
        }

    }
}
