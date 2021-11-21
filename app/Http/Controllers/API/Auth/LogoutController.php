<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\API\BaseController;
use App\Services\Auth\AuthLoggerService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;

class LogoutController extends BaseController
{
    /**
     * Выход пользователя.
     *
     * @param AuthLoggerService $log
     * @return Application
     * @return ResponseFactory
     * @return Response
     * @api {post} /api/logout
     */
    public function logout(AuthLoggerService $log)
    {
        $log->writeLogoutLog(auth()->user()->email);
        // currentAccessToken
        auth()->user()->currentAccessToken()->delete();
        return response(['messsage' => 'пользователь вышел, токен удален !'], 200);
    }
}
