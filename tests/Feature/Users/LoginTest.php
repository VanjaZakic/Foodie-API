<?php

namespace Tests\Feature\Users;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

/**
 * Class LoginTest
 * @package Tests\Feature
 */
class LoginTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var User $admin
     */
    protected $admin;

    public function setUp(): void
    {
        parent::setUp();
        $this->admin = factory(User::class)->states(USER::ROLE_ADMIN)->create();
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
        $this->clientSeed();
        $this->json('POST', 'api/v1/login', [
            'email'    => $this->admin->email,
            'password' => 'wrongPassword'
        ])
            ->assertStatus(400);
    }

    public function test_it_returns_a_token_if_credentials_do_match()
    {
        $this->clientSeed();
        $this->json('POST', 'api/v1/login', [
            'email'    => $this->admin->email,
            'password' => '123456'
        ])
            ->assertJsonStructure([
                'token_type',
                'expires_in',
                'access_token',
                'refresh_token',
            ]);
    }

    public function test_it_returns_a_user_type_if_credentials_do_match()
    {
        $this->clientSeed();
        $this->json('POST', 'api/v1/login', [
            'email'    => $this->admin->email,
            'password' => '123456'
        ])
            ->assertJsonFragment([
                'user_type' => $this->admin->role
            ]);
    }

    private function clientSeed()
    {
        Artisan::call('cache:clear');
        Artisan::call('db:seed --class=OauthClientsTableSeeder');
    }
}
