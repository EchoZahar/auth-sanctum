<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\API\BaseController;
use App\Http\Requests\CheckTokenRequest;
use App\Http\Requests\RefreshTokenRequest;
use App\Models\PersonalAccessToken;
use App\Models\User;
use App\Services\Auth\AuthLoggerService;
use App\Services\Auth\ExpiredAtToken;
use Illuminate\Http\JsonResponse;

class TokenController extends BaseController
{
    /**
     * Проверка токена пользователя
     *
     * @param PersonalAccessToken $token
     * @param CheckTokenRequest $request
     * @return boolean
     * @api {get} /api/check Request user token
     */
    public function checkToken(PersonalAccessToken $token, CheckTokenRequest $request)
    {
        // проверка строки
        $accessToken = $token->findLimitToken($request->token);
        if ($accessToken) {
            return true; // вернет 1
        }
        return false; // ничего не вернет
    }

    /**
     * Обновление токена пользователя
     *
     * @param RefreshTokenRequest $request
     * @param AuthLoggerService $log
     * @param ExpiredAtToken $expired
     * @return JsonResponse
     * @api {post} /api/refresh Request user token:id
     */
    public function refreshToken(RefreshTokenRequest $request, AuthLoggerService $log, ExpiredAtToken $expired)
    {
        // по id получаю токен
        $token = PersonalAccessToken::where('id', $request->token_id)->first();
        if (!$token) {
            return $this->sendError('token not found', 404);
        }
        // по полученому токену из таблицы получаю пользователя
        $user = User::where('id', $token->tokenable_id)->first();
        // если нет полбзователя возвращаю ошибку
        if (!$user) {
            return $this->sendError('user not found', 404);
        }
        // удаление текущего токена
        $user->tokens()->where('id', $token->id)->delete();
//        $user->currentAccessToken()->delete();
        // назначить полученому пользователю новый токен
        $token = $user->createToken(config('app.name'), ['limited:token'])->plainTextToken;
        // время жизни токена
        $expired_at = $expired->expired($token);
        // формирование ответа
        $response = ['user' => auth()->user(), 'token' => $token, 'expired_at' => $expired_at];
        // Записать в лог файл обновление токена
        $log->refreshToken(auth()->user(), $token, $expired_at, $request->server('HTTP_USER_AGENT'), $request->ip());
        // вернуть ответ
        return $this->sendResponse($response, 'токен пользователя обновлен успешно.');
    }
}
