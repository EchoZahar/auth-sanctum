<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\API\BaseController;
use App\Http\Requests\ResetPasswordRequest;
use App\Services\Auth\AuthLoggerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Password;

class ResetPasswordController extends BaseController
{
    /**
     * Сброс пароля пользователя.
     *
     * @param AuthLoggerService $log
     * @return JsonResponse
     * @api {post} /api/reset Request email
     */
    public function resetPassword(AuthLoggerService $log)
    {
        $credentials = request()->validate(['email' => 'required|email']);
        $link = Password::sendResetLink($credentials);
        $log->resetPassword(request()->email, $link);
        $response = [$credentials, $link];
        return $this->sendResponse($response, 'Ссылка с токеном отправлена !');
    }

    /**
     * Проверка ссылки на сброс пароля.
     * (не закончено проверить токен и назначить новый при необходимости)
     *
     * @param ResetPasswordRequest $request
     * @param AuthLoggerService $log
     * @return JsonResponse
     * @api {post} /api/reset Request user, password
     */
    public function resetCheck(ResetPasswordRequest $request, AuthLoggerService $log)
    {
        $reset_status = Password::reset($request->validated(), function ($user, $password) {
            $user->password = $password;
            $user->save();
        });
        if ($reset_status == Password::INVALID_TOKEN) {
            return $this->sendError('что то пошло не так, токен не валидный');
        }
        // удалить токен авторизаций и назначить новый токен и записать в лог
        $response = [
            'user' => $request->user()
        ];
        return $this->sendResponse($response, 'пароль успешно изменен !');
    }
}
