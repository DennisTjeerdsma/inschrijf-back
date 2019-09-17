<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Models\User;

class LoginTest extends TestCase
{
    // Feature test testing all the login features of the application.
    use DatabaseMigrations;
    protected $user;

    public function setUp():void
    {
        parent::setUp();
        $this->user = factory(User::class)->create([
            'password' => \Hash::make('secret')
        ]);
    } 

    /** @test */
    public function it_will_login_a_user()
    {

        $response = $this->post('api/auth/login', [
            'email' => $this->user->email,
            'password' => 'secret'
        ]);

        $response
                ->assertStatus(200)
                ->assertJsonStructure([
                    'token',
                    'type',
                    'expires'
        ]);
    }

    public function it_will_not_login_a_user()
    {
        $response = $this->post('api/auth/login', [
            'email' => $this->user->email,
            'password' => 'not_password'
        ]);

        $response
                ->assertStatus(401)
                ->assertJsonStructure([
                    'error'
                ]);
    }

    public function it_will_logout_a_user()
    {
        $this->post('api/auth/login', [
            'email' => $user->email,
            'password' => 'secret'
        ]);


        $response = $this->post('api/auth/logout')
                    ->assertStatus(200)
                    ->assertJsonStructure([
                        'status',
                        'msg'
                    ]);
    }
}
