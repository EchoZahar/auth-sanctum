<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\API\BaseController;
use App\Services\Auth\AuthLoggerService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laravel\Sanctum\PersonalAccessToken;

class TokenController extends BaseController
{
    /**
     * Проверка токена пользователя
     *
     * @param PersonalAccessToken $token
     * @param Request $request
     * @return Application|ResponseFactory|Response
     * @api {get} /api/check Request user token
     */
    public function checkToken(PersonalAccessToken $token, Request $request)
    {
        $result = $token->findToken($request->input('token'));
        if ($result !== null) {
            return response('true');
        } else {
            return response('false');
        }
    }

    /**
     * Обновление токена пользователя
     *
     * @param Request $request
     * @param AuthLoggerService $log
     * @return JsonResponse
     * @api {post} /api/refresh Request user token:id
     */
    public function refreshToken(Request $request, AuthLoggerService $log)
    {
        $token = auth()->user()->tokens()->where('id', $request->input('token_id'))->first();
        if (!$token) {
            return $this->sendError('error not found', 404);
        }
        $token->delete();
        $token = auth()->user()->createToken(config('app.name'))->plainTextToken;
        $response = ['user' => auth()->user(), 'token' => $token];
        $log->refreshToken(auth()->user(), $token, $request->server('HTTP_USER_AGENT'), $request->ip());
        return $this->sendResponse($response, 'токен пользователя обновлен успешно.');
    }
}
