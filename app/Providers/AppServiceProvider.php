<?php

namespace App\Providers;

use App\Models\PersonalAccessToken;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;
use Laravel\Telescope\Telescope;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Telescope::ignoreMigrations();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Расширение класса
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
        // Установка время жизни токена 30 мин, после истечения 30 минут становится не действительным.
        Sanctum::authenticateAccessTokensUsing(
            static function (PersonalAccessToken $accessToken, bool $is_valid) {
                if (!$accessToken->can('limited:token')) {
                    return $is_valid;
                }
                return $is_valid && $accessToken->created_at->gt(now()->subMinutes(30));
            }
        );

    }
}
