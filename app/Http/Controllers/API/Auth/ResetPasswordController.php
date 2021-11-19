<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\ResetPasswordRequest;
use App\Services\Auth\AuthLoggerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ResetPasswordController extends BaseController
{
    public function resetPassword(AuthLoggerService $log)
    {
        $credentials = request()->validate(['email' => 'required|email']);
        $link = Password::sendResetLink($credentials);
        $log->resetPassword(request()->email, $link);
        $response = [$credentials, $link];
        return $this->sendResponse($response, 'Ссылка с токеном отправлена !');
    }

    public function resetCheck(ResetPasswordRequest $request, AuthLoggerService $log)
    {
        $reset_status = Password::reset($request->validated(), function ($user, $password) {
            $user->password = $password;
            $user->save();
        });
        if ($reset_status == Password::INVALID_TOKEN) {
            return $this->sendError('что то пошло не так, токен не валидный');
        }
        // удалить токен авторизаций и назначить новый токен
        $response = [
            'user' => $request->user()
        ];
        return $this->sendResponse($response, 'пароль успешно изменен !');
    }
}
