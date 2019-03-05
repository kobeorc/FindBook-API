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

    public function setUp($auth = false)
    {
        parent::setUp();
        $this->email = 'test@mail.ru';
        $this->password = '123456';

        if($auth)
        {
            $request = $this->post('login',['email'=>'admin@findbook.info','password'=>'password1234567']);
            $this->token = $request->decodeResponseJson('token');
            $this->header = ['Authorization' => 'Bearer ' . $this->token];
        }
    }
}