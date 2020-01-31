<?php

namespace Tests\Feature\Companies;

use App\Company;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Class CompanyUpdateTest
 * @package Tests\Feature
 */
class CompanyUpdateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var User $admin
     */
    protected $admin;
    private $producerCompany;
    private $customerCompany;

    public function setUp(): void
    {
        parent::setUp();
        $this->admin           = factory(User::class)->states(USER::ROLE_ADMIN)->create();
        $this->producerCompany = factory(Company::class)->states(COMPANY::TYPE_PRODUCER)->create();
        $this->customerCompany = factory(Company::class)->states(COMPANY::TYPE_CUSTOMER)->create();
    }

    public function test_it_shows_unauthorized_if_not_admin()
    {
        $roles = User::availableRoles(USER::ROLE_ADMIN);

        foreach ($roles as $role) {
            $user = factory(User::class)->create([
                'role' => $role,
            ]);

            $this->actingAs($user)
                ->json('PATCH', "api/v1/companies/{$this->producerCompany->id}")
                ->assertStatus(403);
        }
    }

    public function test_it_requires_data()
    {
        $this->actingAs($this->admin)->json('PATCH', "api/v1/companies/{$this->producerCompany->id}")
            ->assertJsonValidationErrors(['name', 'phone', 'address', 'email', 'image', 'type']);
    }

    public function test_it_returns_validation_error_if_over_max_length()
    {
        $this->actingAs($this->admin)->json('PATCH', "api/v1/companies/{$this->producerCompany->id}", [
            'name'  => 'namanamenamenamanamenamenamanamenamenamanamenamenamanamenamenamanamenamenamanamenamenamanamename',
            'phone' => '012345678901234567890123456789',
            'email' => 'emailemailemailemailemailemailemailemailemailemailemailemailemailemailemailemailemail@gmail.com'
        ])
            ->assertJsonValidationErrors(['name', 'phone', 'email']);
    }

    public function test_it_requires_unique_email_and_phone()
    {
        $producer_company_db = factory(Company::class)->states(COMPANY::TYPE_PRODUCER)->create();

        $this->actingAs($this->admin)->json('PATCH', "api/v1/companies/{$this->producerCompany->id}", [
            'email' => $producer_company_db->email,
            'phone' => $producer_company_db->phone
        ])
            ->assertJsonValidationErrors(['email', 'phone']);
    }

    public function test_it_returns_validation_error_if_not_valid_company_type()
    {
        $this->actingAs($this->admin)->json('PATCH', "api/v1/companies/{$this->producerCompany->id}", [
            'type' => 'WrongCompanyType'
        ])
            ->assertJsonValidationErrors(['type']);
    }

    public function test_it_returns_validation_error_if_not_valid_image()
    {
        $this->actingAs($this->admin)->json('PATCH', "api/v1/companies/{$this->producerCompany->id}", [
            'image' => 'NotImage'
        ])
            ->assertJsonValidationErrors(['image']);
    }

    public function test_it_returns_validation_error_if_not_valid_lat_and_lng()
    {
        $this->actingAs($this->admin)->json('PATCH', "api/v1/companies/{$this->producerCompany->id}", [
            'lat' => -91,
            'lng' => -181
        ])
            ->assertJsonValidationErrors(['lat', 'lng']);

        $this->actingAs($this->admin)->json('PATCH', "api/v1/companies/{$this->producerCompany->id}", [
            'lat' => 91,
            'lng' => 81
        ])
            ->assertJsonValidationErrors(['lat', 'lng']);
    }

    public function test_it_returns_validation_error_if_company_type_is_incompatible()
    {
        $this->actingAs($this->admin)->json('PATCH', "api/v1/companies/{$this->producerCompany->id}", [
            "type" => $this->customerCompany->type
        ])
            ->assertJsonValidationErrors(['type']);
    }

    public function test_it_updates_a_company()
    {
        Storage::fake('public');
        $type = COMPANY::TYPE_PRODUCER;

        $image = UploadedFile::fake()->image("new{$type}.jpg");
        $this->actingAs($this->admin)->json('PATCH', "api/v1/companies/{$this->producerCompany->id}", $params = [
            'name'    => 'newcompanyname',
            'phone'   => rand(111111111, 999999999),
            'address' => 'newcompanyaddress',
            'email'   => "new{$type}@gmail.com",
            'image'   => $image,
            'type'    => $type,
            'lat'     => 0,
            'lng'     => 0
        ]);

        $this->assertDatabaseHas('companies', $params);
    }
}
