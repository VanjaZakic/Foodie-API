<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    public function setUp() :void
    {
        parent::setUp();
        Artisan::call('passport:install',['-vvv' => true]);
        $this->admin = factory(User::class)->create([
            'role'     => User::ROLE_ADMIN,
            'password' => '123456'
        ]);
    }

    public function test_it_requires_an_email()
    {
        $this->json('POST', 'api/v1/login')
            ->assertJsonValidationErrors(['email']);
    }

    public function test_it_requires_an_password()
    {
        $this->json('POST', 'api/v1/login')
            ->assertJsonValidationErrors(['password']);
    }

    public function test_it_returns_a_bad_request_if_credentials_dont_match()
    {
        $this->json('POST', 'api/v1/login', [
            'email' => $this->admin->email,
            'password' => 'wrongPassword'
        ])
            ->assertStatus(400);
    }

    public function test_it_returns_a_token_if_credentials_do_match()
    {
        $this->json('POST', 'api/v1/login', [
            'email' => $this->admin->email,
            'password' => '123456'
        ])
            ->assertJsonStructure([
                'access_token'
                ]);
    }

    public function test_it_returns_a_user_type_if_credentials_do_match()
    {
        $this->json('POST', 'api/v1/login', [
            'email' => $this->admin->email,
            'password' => '123456'
        ])
            ->assertJsonFragment([
                'user_type' => $this->admin->role
            ]);
    }
}
