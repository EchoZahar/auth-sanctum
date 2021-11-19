<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register form</title>
</head>
<body>
<form action="{{ url('api/register') }}" method="post">
    @csrf
    <input type="email" name="email" placeholder="inter email">
    <input type="password" name="password" placeholder="inter you password">
    <input type="password" name="password_confirmation" placeholder="confirm you password">
    <button type="submit">submit</button>
</form>
</body>
</html>
