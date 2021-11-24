<?php

namespace Tests\Unit;

use App\Models\User;
use Tests\TestCase;

class RegisterUserTest extends TestCase
{
    /**
     * Тест если email уже сущществует в БД
     * @test unit
     */
    public function failed_if_isset_email()
    {
        $user = User::find(1);
        $response = $this->postJson('/api/register', [
            'email'                 => $user->email,
            'password'              => 'password',
            'password_confirmation' => 'password'
        ]);

        $response->dump();
        $response->status();
        $this->assertGuest($guard = null);
    }

    /**
     * Тест на подтверждение пароля
     * @test unit
     */
    public function failed_password_confirmation()
    {
        $response = $this->postJson('/api/register', [
            'email'                 => 'admin@example.com',
            'password'              => 'password',
            'password_confirmation' => 'wrong_password'
        ]);
        $response->dumpHeaders();
        $response->dumpSession();
        $response->dump();
        $response->status();
    }

    /**
     * Тест егистрации нового пользователя
     * @test unit
     */
    public function success_register_new_user()
    {
        $response = $this->postJson('/api/register', [
            'email'                 => 'admin@example.com',
            'password'              => 'password',
            'password_confirmation' => 'password'
        ]);
        $response->dumpHeaders();
        $response->dumpSession();
        $response->dump();
        $response->status();
        $this->assertEquals(200, $response->status());
    }
}
