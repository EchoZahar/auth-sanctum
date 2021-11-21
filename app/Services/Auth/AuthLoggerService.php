<?php


namespace App\Services\Auth;


class AuthLoggerService
{
    /**
     * Лрогирование авторизаций пользоватеолей
     *
     * @param $user
     * @param $token
     * @param $expired_at
     * @param $userAgent
     * @param $ip
     */
    function writeSignInLog($user, $token, $expired_at, $userAgent, $ip)
    {
        info('Выполнен вход пользователем email: ' . $user->email .
            ', id: ' . $user->id .
            ', token: ' . $token .
            ', token expired: ' . $expired_at .
            ', user agent: ' . $userAgent .
            ', IP: ' . $ip . '.'
        );
    }

    /**
     * Логирование регистрации пользователей
     *
     * @param $user
     * @param $token
     * @param $expired_at
     * @param $userAgent
     * @param $ip
     */
    function writeSignUpLog($user, $token, $expired_at,  $userAgent, $ip)
    {
        info('Регистрация нового пользователя email: ' . $user->email . ', id: ' . $user->id .
            ', token: ' . $token .
            ', token expired: ' . $expired_at .
            ', user agent: ' . $userAgent .
            ', IP: ' . $ip . '.'
        );
    }

    /**
     * Логировнаие вышедших пользователей
     *
     * @param $email
     */
    function writeLogoutLog($email)
    {
        info('Пользователь вышел: ' . $email);
    }

    /**
     * запись в лог обновление токена пользователя
     *
     * @param $email
     * @param $token
     * @param $expired_at
     * @param $userAgent
     * @param $ip
     */
    function refreshToken($email, $token, $expired_at, $userAgent, $ip)
    {
        info('Обновлен токен пользователя: ' . $email .
            ', токен: ' . $token .
            ', token expired: ' . $expired_at .
            ', данные user agent обновивщего токен пользователя: ' . $userAgent .
            ', ip пользователя: ' . $ip . '.'
        );
    }

    /**
     * все попытки сброса пароля
     *
     * @param $email
     * @param $userAgent
     * @param $ip
     */
    function resetPassword($email, $userAgent, $ip)
    {
        info('Сброс пароля, отправлена ссылка на email:
            ' . $email .
            ', данные user agent обновивщего токен пользователя: ' . $userAgent .
            ', ip пользователя: ' . $ip . '.'
        );
    }

    /**
     * Проверка на сброс
     *
     * @param $email
     * @param $token
     * @param $expired_at
     * @param $userAgent
     * @param $ip
     */
    function resetChecked($email, $token, $expired_at, $userAgent, $ip)
    {
        info('Обновлен пароль пользователя email: ' . $email .
            ', токен: ' . $token .
            ', token expired: ' . $expired_at .
            ', user agent: ' . $userAgent .
            ', ip пользователя: ' . $ip . '.'
        );
    }
}
