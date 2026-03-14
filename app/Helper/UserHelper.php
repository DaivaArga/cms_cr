<?php

namespace App\Helper;

use Illuminate\Http\JsonResponse;

class UserHelper
{
    /**
     * Return a success response.
     *
     * @param string $message
     * @param mixed $data
     * @param int $statusCode
     * @return JsonResponse
     */
    public static function successResponse(string $message, $data = null, int $statusCode = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }

    /**
     * Return an error response.
     *
     * @param string $message
     * @param int $statusCode
     * @param array $errors
     * @return JsonResponse
     */
    public static function errorResponse(string $message, int $statusCode = 400, array $errors = []): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ], $statusCode);
    }

    /**
     * Return a validation error response.
     *
     * @param array $errors
     * @return JsonResponse
     */
    public static function validationErrorResponse(array $errors): JsonResponse
    {
        return self::errorResponse('Validasi gagal', 422, $errors);
    }

    /**
     * Return a not found response.
     *
     * @param string $message
     * @return JsonResponse
     */
    public static function notFoundResponse(string $message = 'Data tidak ditemukan'): JsonResponse
    {
        return self::errorResponse($message, 404);
    }

    /**
     * Return an unauthorized response.
     *
     * @param string $message
     * @return JsonResponse
     */
    public static function unauthorizedResponse(string $message = 'Unauthorized'): JsonResponse
    {
        return self::errorResponse($message, 401);
    }

    /**
     * Return a forbidden response.
     *
     * @param string $message
     * @return JsonResponse
     */
    public static function forbiddenResponse(string $message = 'Forbidden'): JsonResponse
    {
        return self::errorResponse($message, 403);
    }

    /**
     * Return an internal server error response.
     *
     * @param string $message
     * @return JsonResponse
     */
    public static function serverErrorResponse(string $message = 'Terjadi kesalahan pada server'): JsonResponse
    {
        return self::errorResponse($message, 500);
    }
}
