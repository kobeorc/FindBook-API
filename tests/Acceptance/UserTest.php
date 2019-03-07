<?php

namespace Tests\Acceptance;


use Illuminate\Foundation\Testing\DatabaseTransactions;

/** @group Acceptance */
class UserTest extends TestCase
{
    use DatabaseTransactions;

    public function testRegister_success()
    {
        $request = $this->post('api/register', ['email' => $this->email, 'password' => $this->password, 'password_confirmation' => $this->password, 'name' => 'Joe'], ['Accept' => 'application/json']);
        $request->assertStatus(201);
        $this->assertDatabaseHas('users', ['email' => $this->email]);

    }

    public function testRegisterSilent_success()
    {
        $request = $this->post('api/register/silent', [], ['Accept' => 'application/json']);
        $request->assertStatus(200);
        $request->assertJsonStructure(['token']);
    }

    public function tetLogin_success()
    {
        $request = $this->post('api/login', ['email' => $this->email, 'password' => $this->password], ['Accept' => 'application/json']);
        $request->assertStatus(200);
    }
}