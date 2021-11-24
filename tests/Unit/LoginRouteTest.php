<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class LoginRouteTest extends TestCase
{
    /**
     * @test Авторизация
     * После выполнения команды php artisan db:seed
     * @return void
     */
    public function test_making_an_api_request()
    {
        $user = User::find(1);
        $response = $this->postJson('api/login', [
            'email'     => $user->email,
            'password'  => 'password'
        ]);
        $response->dumpHeaders();
        $response->dumpSession();
        $response->dump();
        $response->status();
        $this->be($user);
        $this->assertAuthenticatedAs($user, $guard = null);
        $this->assertEquals(200, $response->status());
    }

    /**
     * @test не верный пароль 401
     * @return void
     */
    public function send_error_user_wrong_password()
    {
        $user = User::find(1);
        $response = $this->postJson('api/login', [
            'email'     => $user->email,
            'password'  => 'wrong_password'
        ]);
        $response->dump();
        $response->status();
        $this->assertGuest($guard = null);
    }

    /**
     * @test не верный email 401
     * @return void
     */
    public function send_error_user_wrong_email()
    {
        $user = User::find(1);
        $response = $this->postJson('api/login', [
            'email'     => '12' . $user->email,
            'password'  => 'password'
        ]);
        $response->dump();
        $response->status();
        $this->assertGuest($guard = null);
    }
}
