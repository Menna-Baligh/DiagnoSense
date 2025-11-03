<?php
namespace App\Http\Responses;
class ApiResponse{
    public static function success($message, $data = null, $statusCode){
        $response = [
            'success' => true,
            'message' => $message,
        ];
        if (!is_null($data)) {
            $response['data'] = $data;
        }
        return response()->json($response, $statusCode);
    }

    public static function error($message, $errors = null, $statusCode){
        $response = [
            'success' => false,
            'message' => $message,
        ];
        if (!is_null($errors)) {
            $response['errors'] = $errors;
        }
        return response()->json($response, $statusCode);

    }
}
