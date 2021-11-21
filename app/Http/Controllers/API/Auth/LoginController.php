<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\API\BaseController;
use App\Http\Requests\UserSignInRequest;
use App\Models\User;
use App\Services\Auth\AuthLoggerService;
use App\Services\Auth\ExpiredAtToken;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class LoginController extends BaseController
{
    /**
     * Авторизация
     * @param UserSignInRequest $request
     * @param AuthLoggerService $log
     * @param ExpiredAtToken $expired
     * @return JsonResponse
     * @api {post} /api/login Request User email, password
     */
    public function signIn(UserSignInRequest $request, AuthLoggerService $log, ExpiredAtToken $expired)
    {
        // проверка аутентификаций
        $user = User::where('email', $request->email)->first();
        if ($user || Hash::check($request->password, $user->password)) {
            // назначить токен пользователю
            $token = $user->createToken(config('app.name'), ['limited:token'])->plainTextToken;
            // запись время жизни токена
            $expired_at = $expired->expired($token);
            // запись в log файл
            $log->writeSignInLog($user, $token, $expired_at, $request->server('HTTP_USER_AGENT'), $request->ip());
            // формирование ответа пользователю
            $response = [
                'user' => $user,
                'token' => $token,
                'expired_at' => $expired_at
            ];
            // вернуть ответ пользователю
            return $this->sendResponse($response, 'User signed in');
        } else {
            // вернуть ответ в случае ошибки
            return $this->sendError('Unauthorised.', ['error' => 'Unauthorised']);
        }
    }
}
