<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ResetPasswordRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Password;

class PasswordResetController extends Controller
{
    /**
     * Проверка проверка ссылки на восстановление пароля
     * @param ResetPasswordRequest $request
     * @return RedirectResponse
     * @api {post} /reset/check Request user, password
     */
    public function resetCheck(ResetPasswordRequest $request)
    {
        $reset_status = Password::reset($request->validated(), function ($user, $password) {
            $user->password = $password;
            $user->save();
        });
        if ($reset_status == Password::INVALID_TOKEN) {
            return back()->withErrors(['message' => 'Что то пошло не так, проверте введенные данные !']);
        }
        return redirect()->route('login')->with(['success' => 'Пароль успешно сброшен !']);
    }
}
