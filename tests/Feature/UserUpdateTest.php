<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->withExceptionHandling();
    }

    //    public function test_it_stores_data()
//    {
//        $admin = factory(User::class)->states('admin')->create();
//
//        $this->actingAs($admin)->json('PUT', "api/v1/users/{$admin->id}", [
//            'first_name' => '',
//        ])
//            ->assertJsonValidationErrors(['first_name']);
//    }

    public function test_user_can_update_self()
    {
        $roles = User::$roles;

        foreach ($roles as $role) {
            $user = factory(User::class)->states($role)->create();

            $this->actingAs($user)->json('PUT', "api/v1/users/{$user->id}", [
                "first_name" => $firstname = "newFirstName",
                "last_name"  => $lastname = "newLastName",
                "phone"      => $phone = "0123456789",
                "address"    => $address = "newAddress",
                "email"      => $email = "newemail@gmail.com",
                "role"       => $role = $user->role,
                "company_id" => $company_id = $user->company_id
            ]);


            $this->assertDatabaseHas('users', [
                'first_name' => $firstname,
                'last_name'  => $lastname,
                'phone'      => $phone,
                'address'    => $address,
                'email'      => $email,
//                'role'       => $role,
//                'company_id' => $company_id
            ]);
        }
    }

    public function test_users_cant_update_self_role()
    {
        $roles = User::$roles;

        foreach ($roles as $role) {
            $user = factory(User::class)->states($role)->create();

            $this->actingAs($user)->json('PUT', "api/v1/users/{$user->id}", [
                "first_name" => "newFirstName",
                "last_name"  => "newLastName",
                "phone"      => "0123456789",
                "address"    => "newAddress",
                "email"      => "newemail@gmail.com",
                "role"       => "invalidRole",
                "company_id" => $user->company_id
            ])
                ->assertStatus(403);
        }
    }

    public function test_users_cant_update_self_company_id()
    {
        $roles = User::$roles;

        foreach ($roles as $role) {
            $user = factory(User::class)->states($role)->create();

            $this->actingAs($user)->json('PUT', "api/v1/users/{$user->id}", [
                "first_name" => "newFirstName",
                "last_name"  => "newLastName",
                "phone"      => "0123456789",
                "address"    => "newAddress",
                "email"      => "newemail@gmail.com",
                "role"       => $user->role,
                "company_id" => "invalidCompanyId"
            ])
                ->assertStatus(403);
        }
    }

    public function test_company_admins_can_downgrade_self_company_user()
    {
        $producer_admin = factory(User::class)->states('producer_admin')->create();
        $producer_user  = factory(User::class)->states('producer_user')->create([
            'company_id' => $producer_admin->company_id
        ]);

        $this->actingAs($producer_admin)->json('PUT', "api/v1/users/{$producer_user->id}", [
            "first_name" => $firstname = "newFirstName",
            "last_name"  => $lastname = "newLastName",
            "phone"      => $phone = "0123456789",
            "address"    => $address = "newAddress",
            "email"      => $email = "newemail@gmail.com",
            "role"       => $role = 'user',
            "company_id" => $company_id = null
        ]);


        $this->assertDatabaseHas('users', [
            'first_name' => $firstname,
            'last_name'  => $lastname,
            'phone'      => $phone,
            'address'    => $address,
            'email'      => $email,
            'role'       => $role,
            'company_id' => $company_id
        ]);


    }
}
