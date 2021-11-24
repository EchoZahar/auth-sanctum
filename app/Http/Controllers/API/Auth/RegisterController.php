<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\API\BaseController;
use App\Http\Requests\UserSignUpRequest;
use App\Models\User;
use App\Services\Auth\AuthLoggerService;
use App\Services\Auth\ExpiredAtToken;
use Illuminate\Http\JsonResponse;

class RegisterController extends BaseController
{
    /**
     * Регистрация пользователя (Аутентификация)
     *
     * @param UserSignUpRequest $request Проверка входящего запроса
     * @param AuthLoggerService $log Записать лог о регистраций нового пользователя
     * @param ExpiredAtToken $expired Записать время жизни токена
     * @return JsonResponse
     * @api {post} /api/register Request email, password, password_confirmation
     */
    public function signUp(UserSignUpRequest $request, AuthLoggerService $log, ExpiredAtToken $expired)
    {
        $credentials = $request->only('email', 'password');
        $credentials['password'] = bcrypt($request->input('password'));
        $user = User::create($credentials);
        if ($user) {
            $token = $user->createToken(config('app.name'), ['limited:token'])->plainTextToken;
            $expired_at = $expired->expired($token);
            $response = ['user' => $user, 'token' => $token, 'expired_at' => $expired_at];
            $log->writeSignUpLog($user, $token, $expired_at, $request->server('HTTP_USER_AGENT'), $request->ip());
            return $this->sendResponse($response, 'Зарегестрирован новый пользователь !');
        } else {
            return $this->sendError('unregistered', ['error' => 'что то пошло не так !']);
        }
    }
}
