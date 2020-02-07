<?php

namespace Tests\Feature\Companies;

use App\Company;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Class CompanyStoreTest
 * @package Tests\Feature
 */
class CompanyStoreTest extends TestCase
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

    public function test_it_shows_unauthorized_if_not_admin()
    {
        $roles = User::availableRoles(USER::ROLE_ADMIN);

        foreach ($roles as $role) {
            $user = factory(User::class)->create([
                'role' => $role,
            ]);

            $this->actingAs($user)
                ->json('POST', 'api/v1/companies')
                ->assertStatus(403);
        }
    }

    public function test_it_requires_data()
    {
        $this->actingAs($this->admin)->json('POST', 'api/v1/companies')
            ->assertJsonValidationErrors(['name', 'phone', 'address', 'email', 'image', 'type']);
    }

    public function test_it_returns_validation_error_if_over_max_length()
    {
        $this->actingAs($this->admin)->json('POST', 'api/v1/companies', [
            'name'  => 'namanamenamenamanamenamenamanamenamenamanamenamenamanamenamenamanamenamenamanamenamenamanamename',
            'phone' => '012345678901234567890123456789',
            'email' => 'emailemailemailemailemailemailemailemailemailemailemailemailemailemailemailemailemail@gmail.com'
        ])
            ->assertJsonValidationErrors(['name', 'phone', 'email']);
    }

    public function test_it_requires_unique_email_and_phone()
    {
        $company = factory(Company::class)->states(COMPANY::TYPE_PRODUCER)->create();

        $this->actingAs($this->admin)->json('POST', 'api/v1/companies', [
            'email' => $company->email,
            'phone' => $company->phone
        ])
            ->assertJsonValidationErrors(['email', 'phone']);
    }

    public function test_it_returns_validation_error_if_not_valid_company_type()
    {
        $this->actingAs($this->admin)->json('POST', 'api/v1/companies', [
            'type' => 'WrongCompanyType'
        ])
            ->assertJsonValidationErrors(['type']);
    }

    public function test_it_returns_validation_error_if_not_valid_image()
    {
        $this->actingAs($this->admin)->json('POST', 'api/v1/companies', [
            'image' => 'NotImage'
        ])
            ->assertJsonValidationErrors(['image']);
    }

    public function test_it_returns_validation_error_if_not_valid_lat_and_lng()
    {
        $this->actingAs($this->admin)->json('POST', 'api/v1/companies', [
            'lat' => -91,
            'lng' => -181
        ])
            ->assertJsonValidationErrors(['lat', 'lng']);

        $this->actingAs($this->admin)->json('POST', 'api/v1/companies', [
            'lat' => 91,
            'lng' => 81
        ])
            ->assertJsonValidationErrors(['lat', 'lng']);
    }

    public function test_it_stores_a_company()
    {
        Storage::fake('public');

        $types = COMPANY::$types;
        foreach ($types as $type) {
            $company = factory(COMPANY::class)->states($type)->make();
            $image   = $company->image;
            $this->actingAs($this->admin)->json('POST', 'api/v1/companies', $params = $company->toArray());

            $path            = 'images/' . $image->hashName();
            $params['image'] = $path;
            Storage::disk('public')->assertExists($path);

            $this->assertDatabaseHas('companies', $params);
        }
    }
}
