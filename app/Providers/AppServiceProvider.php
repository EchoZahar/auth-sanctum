<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;
use App\Models\PersonalAccessToken;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Регистрация расширяемой модели PersonalAccessToken в которой указывается длина токена.
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
        // Установка время жизни токена 30 мин, после истечения 30 минут становится не действительным.
        Sanctum::authenticateAccessTokensUsing(
            static function (PersonalAccessToken $accessToken, bool $is_valid) {
                if (!$accessToken->can('limited:token')) {
                    return $is_valid;
                }
//                $accessToken->expired_at = $accessToken->created_at->addMinutes(30);
                return $is_valid && $accessToken->created_at->gt(now()->subMinutes(30));
            }
        );

    }
}
