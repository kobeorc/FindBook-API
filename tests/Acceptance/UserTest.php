<?php
declare(strict_types=1);

namespace Tests\Acceptance;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\Acceptance\Structures\ValidateErrorStructure;

/** @group Acceptance */
class UserTest extends TestCase
{
    use DatabaseTransactions;

    /** @url api/register */
    public function testRegister_success()
    {
        $response = $this->post('api/register', ['email' => $this->notExistsEmail, 'password' => $this->notExistsPassword, 'password_confirmation' => $this->notExistsPassword, 'name' => 'Joe'], ['Accept' => 'application/json']);
        $response->assertStatus(201);
        $this->assertDatabaseHas('users', ['email' => $this->notExistsEmail]);
    }

    public function testRegisterNoEmail_failed()
    {
        $response = $this->post('api/register', ['password' => $this->notExistsPassword, 'password_confirmation' => $this->notExistsPassword, 'name' => 'Joe'], ['Accept' => 'application/json']);
        $response->assertStatus(422);
        $this->assertDatabaseMissing('users', ['email' => $this->notExistsEmail]);
        $response->assertJsonStructure(ValidateErrorStructure::$structure);
        $this->checkStructures(ValidateErrorStructure::$types, $response->decodeResponseJson());
    }

    public function testRegisterNoConfirmationPassword_failed()
    {
        $response = $this->post('api/register', ['email' => $this->notExistsEmail, 'password' => $this->notExistsPassword, 'name' => 'Joe'], ['Accept' => 'application/json']);
        $response->assertStatus(422);
        $this->assertDatabaseMissing('users', ['email' => $this->notExistsEmail]);
        $response->assertJsonStructure(ValidateErrorStructure::$structure);
        $this->checkStructures(ValidateErrorStructure::$types, $response->decodeResponseJson());
    }

    public function testRegisterNoName_failed()
    {
        $response = $this->post('api/register', ['email' => $this->notExistsEmail, 'password' => $this->notExistsPassword, 'password_confirmation' => $this->notExistsPassword], ['Accept' => 'application/json']);
        $response->assertStatus(422);
        $this->assertDatabaseMissing('users', ['email' => $this->notExistsEmail]);
        $response->assertJsonStructure(ValidateErrorStructure::$structure);
        $this->checkStructures(ValidateErrorStructure::$types, $response->decodeResponseJson());
    }

    /** @url api/register/silent */
    public function testRegisterSilent_success()
    {
        $response = $this->post('api/register/silent', ['token' => config('app.s')], ['Accept' => 'application/json']);
        $response->assertStatus(200);
        $response->assertJsonStructure(['token']);
    }

    public function testRegisterSilentNoToken_failed()
    {
        $response = $this->post('api/register/silent', [], ['Accept' => 'application/json']);
        $response->assertStatus(422);
        $response->assertJsonStructure(ValidateErrorStructure::$structure);
        $this->checkStructures(ValidateErrorStructure::$types, $response->decodeResponseJson());
    }

    /**
     * @url api/login
     */
    public function testLogin_success()
    {
        $response = $this->post('api/login', ['email' => $this->testUserEmail, 'password' => $this->testUserPassword], ['Accept' => 'application/json']);
        $response->assertStatus(200);
        $response->assertJsonStructure(['token']);
    }

    public function testLoginNoEmail_failed()
    {
        $response = $this->post('api/login', ['password' => $this->testUserPassword], ['Accept' => 'application/json']);
        $response->assertStatus(422);
        $response->assertJsonStructure(ValidateErrorStructure::$structure);
        $this->checkStructures(ValidateErrorStructure::$types, $response->decodeResponseJson());
    }

    public function testLoginNoPassword_failed()
    {
        $response = $this->post('api/login', ['email' => $this->testUserEmail], ['Accept' => 'application/json']);
        $response->assertStatus(422);
        $response->assertJsonStructure(ValidateErrorStructure::$structure);
        $this->checkStructures(ValidateErrorStructure::$types, $response->decodeResponseJson());
    }

    public function testLoginNoCredential_failed()
    {
        $response = $this->post('api/login', ['email' => $this->notExistsEmail, 'password' => $this->notExistsPassword], ['Accept' => 'application/json']);
        $response->assertStatus(422);
        $response->assertJsonStructure(ValidateErrorStructure::$structure);
        $this->checkStructures(ValidateErrorStructure::$types, $response->decodeResponseJson());
    }
}
