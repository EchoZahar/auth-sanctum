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
     * Сброс пароля пользователя по email.
     *
     * @param AuthLoggerService $log запись в лог файл
     * @return JsonResponse
     * @api {post} /api/reset Request email
     */
    public function resetPassword(AuthLoggerService $log)
    {
        $credentials = request()->validate(['email' => 'required|email']);
        if (!$credentials) {
            return $this->sendError('введите email');
        }
        $user = User::where('email', $credentials)->first();
        if (!$user) {
            return $this->sendError('пользователь не найден !');
        }
        Password::sendResetLink($credentials);
        $log->resetPassword(request()->email, request()->server('HTTP_USER_AGENT'), request()->ip());
        return $this->sendResponse($credentials['email'], 'Ссылка с токеном отправлена на email: ' . $credentials['email'] . ' !');
    }
}
