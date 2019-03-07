<?php

namespace Tests\Acceptance;


abstract class TestCase extends \Tests\TestCase
{
    /** @var string */
    protected $email;
    /** @var string */
    protected $password;
    /** @var string */
    protected $token;
    /** @var array */
    protected $header;

    public function setUp($auth = false): void
    {
        parent::setUp();
        $this->email = 'test@mail.ru';
        $this->password = '123456';

        if ($auth) {
            $response = $this->post('api/login', ['email' => 'admin@findbook.info', 'password' => 'password123456'], ['Accept' => 'application/json']);
            $this->token = $response->decodeResponseJson('token');
            $this->header = ['Authorization' => 'Bearer ' . $this->token, 'Accept' => 'application/json'];
        }
    }
}