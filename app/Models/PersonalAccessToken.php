<?php

namespace App\Models;

use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;

class PersonalAccessToken extends SanctumPersonalAccessToken
{

    protected $casts = [
        'expired_at' => 'datetime',
        'abilities' => 'json',
        'last_used_at' => 'datetime',
    ];
    protected $guarded = [];

    /**
     * проверка токена.
     * @param $token
     * @return PersonalAccessToken
     */
    public function findLimitToken($token)
    {
        return parent::findToken($token);
    }

    /**
     * информация о последнем применении токена last_used_at,
     * true включает запись о применении токена
     * false дает прирост производительности, не перезаписывая действия в БД
     * @param array $options
     * @return false
     */
    public function save(array $options = [])
    {
        $changes = $this->getDirty();
        if (!array_key_exists('last_used_at', $changes) || count($changes) >= 1) {
            parent::save();
        }
        return false;
    }
}
