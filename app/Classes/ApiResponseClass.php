<?php

namespace App\Classes;

class ApiResponseClass
{
    public static function success($data, $message = 'Success')
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data,
        ], 200);
    }

    public static function error($errors, $message = 'Error', $status = 422)
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'errors' => $errors,
        ], $status);
    }
}
