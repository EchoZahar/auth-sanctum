<?php


namespace App\Services\Auth;

use App\Models\PersonalAccessToken;

class ExpiredAtToken
{
    static function expired($token)
    {
        $accessToken = PersonalAccessToken::findToken($token); //->select('expired_at')->first();
        $accessToken['expired_at'] = $accessToken->created_at->addMinutes(30);
        $accessToken->save();
        return $accessToken->expired_at;
    }
}
