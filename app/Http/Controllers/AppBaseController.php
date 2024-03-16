<?php

namespace App\Http\Controllers;

use App\Utils\ResponseUtil;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class AppBaseController extends Controller
{
    /**
     * @param mixed $data
     *
     * @return JsonResponse
     */
    public function successResponse($data): JsonResponse
    {
        return response()->json(ResponseUtil::generateResponse(
            'SUCCESS',
            'Your request is successfully executed',
            $data
        ), Response::HTTP_OK);
    }

    /**
     * @param string $message
     * @param int $code
     * 
     * @return JsonResponse
     */
    public function errorResponse($message, $code = 500): JsonResponse
    {
        return response()->json(ResponseUtil::generateResponse(
            'ERROR',
            $message,
            []
        ), $code);
    }

    /**
     * @param mixed $data
     *
     * @return JsonResponse
     */
    public function loginSuccess($data): JsonResponse
    {
        return response()->json(ResponseUtil::generateResponse(
            'SUCCESS',
            'Login Successful',
            $data
        ), Response::HTTP_OK);
    }

    /**
     * @param mixed $data
     *
     * @return JsonResponse
     */
    public function changePasswordSuccess($data): JsonResponse
    {
        return response()->json(ResponseUtil::generateResponse(
            'SUCCESS',
            $data,
            []
        ), Response::HTTP_OK);
    }
}
