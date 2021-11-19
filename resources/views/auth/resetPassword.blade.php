<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Сброс пароля</title>
</head>
<body>
<form class="form-container" action="api/reset/check" method="POST">
    <h2>восстановить пароль ?</h2>

    <input type="email" name="email" placeholder="Введите email" value="{{ request()->get('email') }}">
    <input type="password" name="password" placeholder="введите новый пароль">
    <input type="password" name="password_confirmation" placeholder="подтвертдите введеный пароль">
    <input type="hidden" name="token" placeholder="token" value="{{ request()->get('token') }}">

    <button type="submit">сбросить</button>
</form>
</body>
</html>
