<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\API\BaseController;
use App\Models\User;
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
        // запрос email пользователя
        $credentials = request()->validate(['email' => 'required|email']);
        if (!$credentials) {
            return $this->sendError('введите email');
        }
        // нахожу пользователя
        $user = User::where('email', $credentials)->first();
        // если нет пользователя возвращаю ошибку
        if (!$user) {
            return $this->sendError('пользователя не найдено !');
        }
        // генерация ссылки токена и email обращение к интерфейсу CanResetPassword
        Password::sendResetLink($credentials);
        // запись в лог файл
        $log->resetPassword(request()->email, request()->server('HTTP_USER_AGENT'), request()->ip());

        return $this->sendResponse($credentials['email'], 'Ссылка с токеном отправлена на email: ' . $credentials['email'] . ' !');
    }
}
