<?php

namespace Tests\Acceptance;

use Illuminate\Foundation\Testing\DatabaseTransactions;

/** @group Acceptance */
class UserTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @group Acceptance
     */
    public function testRegister_success()
    {
        $request = $this->post('/register', ['email' => $this->email, 'password' => $this->password, 'password_confirmation' => $this->password, 'name' => 'Joe']);
        $request->assertStatus(201);
        $this->assertDatabaseHas('users', ['email' => $this->email]);

    }

    public function testRegisterSilent_success()
    {
        $request = $this->post('register/silent');
        $request->assertStatus(200);
        $request->assertJsonStructure(['token']);
    }

    public function tetLogin_success()
    {
        $request = $this->post('login', ['email' => $this->email, 'password' => $this->password]);
        $request->assertStatus(200);
    }
}