<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\API\BaseController;
use App\Http\Requests\UserSignUpRequest;
use App\Models\User;
use App\Services\Auth\AuthLoggerService;

class RegisterController extends BaseController
{
    /**
     * Регистрация пользователя (Аутентификация)
     *
     * @param UserSignUpRequest $request
     * @param AuthLoggerService $log
     * @return \Illuminate\Http\JsonResponse
     * @api {post} /api/register Request email, password, password_confirmation
     */
    public function signUp(UserSignUpRequest $request, AuthLoggerService $log)
    {
        $data = $request->input();
        $data['password'] = bcrypt($request->input('password'));
        $user = User::create($data);
        $token = $user->createToken(config('app.name'))->plainTextToken;
        $response = ['user' => $user, 'token' => $token];

        $log->writeSignUpLog($user, $token, $request->server('HTTP_USER_AGENT'), $request->ip());

        return $this->sendResponse($response, 'Зарегестрирован новый пользователь !');
    }
}
