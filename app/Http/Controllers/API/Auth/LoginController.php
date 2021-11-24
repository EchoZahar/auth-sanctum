<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\API\BaseController;
use App\Http\Requests\UserSignInRequest;
use App\Models\User;
use App\Services\Auth\AuthLoggerService;
use App\Services\Auth\ExpiredAtToken;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends BaseController
{
    /**
     * Авторизация
     * @param UserSignInRequest $request проверка запроса
     * @param AuthLoggerService $log  запись в log файл
     * @param ExpiredAtToken $expired запись время жизни токена
     * @return JsonResponse ответ пользователю
     * @api {post} /api/login Request User email, password
     */
    public function signIn(UserSignInRequest $request, AuthLoggerService $log, ExpiredAtToken $expired)
    {
        $credentials = $request->only('email', 'password');
//        $user = User::where('email', $request->email)->first();
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken(config('app.name'), ['limited:token'])->plainTextToken;
            $expired_at = $expired->expired($token);
            $log->writeSignInLog($user, $token, $expired_at, $request->server('HTTP_USER_AGENT'), $request->ip());
            $response = [
                'user' => $user,
                'token' => $token,
                'expired_at' => $expired_at
            ];
            return $this->sendResponse($response, 'User signed in');
        } else {
            return $this->sendError('Unauthorised.', ['error' => 'Unauthorised']);
        }
    }
}
