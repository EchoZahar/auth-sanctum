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
     * @param UserSignUpRequest $request
     * @param AuthLoggerService $log
     * @param ExpiredAtToken $expired
     * @return JsonResponse
     * @api {post} /api/register Request email, password, password_confirmation
     */
    public function signUp(UserSignUpRequest $request, AuthLoggerService $log, ExpiredAtToken $expired)
    {
        $data['email'] = $request->email;
        $data['password'] = bcrypt($request->password);
        // Добавить нового пользователя
        $user = User::create($data);
        if ($user) {
            // Добавить токен пользователю
            $token = $user->createToken(config('app.name'), ['limited:token'])->plainTextToken;
            // Записать время жизни пользователя
            $expired_at = $expired->expired($token);
            // Формирование ответа
            $response = ['user' => $user, 'token' => $token, 'expired_at' => $expired_at];
            // Записать лог о регистраций нового пользователя
            $log->writeSignUpLog($user, $token, $expired_at, $request->server('HTTP_USER_AGENT'), $request->ip());
            // вернуть ответ
            return $this->sendResponse($response, 'Зарегестрирован новый пользователь !');
        } else {
            // вернуть ошибки
            return $this->sendError('unregistered', ['error' => 'что то пошло не так !']);
        }

    }
}
