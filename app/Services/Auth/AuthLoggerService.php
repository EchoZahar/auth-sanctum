<?php


namespace App\Services\Auth;


class AuthLoggerService
{
    /**
     * Лрогирование авторизаций пользоватеолей
     *
     * @param $user
     * @param $token
     * @param $userAgent
     * @param $ip
     */
    function writeSignInLog($user, $token, $userAgent, $ip)
    {
        info('Выполнен вход пользователем email: ' . $user->email .
            ', id: ' . $user->id .
            ', token: ' . $token .
            ', user agent: ' . $userAgent .
            ', IP: ' . $ip . '.'
        );
    }

    /**
     * Логирование регистраций пользователей
     *
     * @param $user
     * @param $token
     * @param $userAgent
     * @param $ip
     */
    function writeSignUpLog($user, $token, $userAgent, $ip)
    {
        info('Регистрация нового пользователя email: ' . $user->email . ', id: ' . $user->id .
            ', token: ' . $token .
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
     * @param $userAgent
     * @param $ip
     */
    function refreshToken($email, $token, $userAgent, $ip)
    {
        info('Обновлен токен пользователя: ' . $email .
            ', токен: ' . $token .
            ', user agent: ' . $userAgent .
            ', ip пользователя: ' . $ip . '.'
        );
    }

    /**
     * все попытки сброса пароля
     *
     * @param $email
     * @param $url
     */
    function resetPassword($email, $url)
    {
        info('Сброс пароля, отправлена ссылка на email:
            ' . $email . ', route: ' . $url . '.'
        );
    }

    /**
     * Проверка на сброс
     * @param $email
     * @param $token
     * @param $userAgent
     * @param $ip
     */
    function resetChecked($email, $token, $userAgent, $ip)
    {
        info('Обновлен пароль пользователя email: ' . $email .
            ', токен: ' . $token .
            ', user agent: ' . $userAgent .
            ', ip пользователя: ' . $ip . '.'
        );
    }
}
