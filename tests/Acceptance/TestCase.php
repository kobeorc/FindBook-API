<?php
declare(strict_types=1);

namespace Tests\Acceptance;


abstract class TestCase extends \Tests\TestCase
{
    /** @var string */
    protected $notExistsEmail;
    /** @var string */
    protected $notExistsPassword;
    /** @var string */
    protected $token;
    /** @var array */
    protected $header;

    /** @var string */
    protected $testUserEmail;

    /** @var string */
    protected $testUserPassword;

    public function setUp($auth = false): void
    {
        parent::setUp();

        $this->notExistsEmail = 'test@mail.ru';
        $this->notExistsPassword = '123456';

        $this->testUserEmail = 'userForTest@mail.ru';
        $this->testUserPassword = 'passwordForTest';


        if ($auth) {
            $response = $this->post('api/login', ['email' => $this->testUserEmail, 'password' => $this->testUserPassword], ['Accept' => 'application/json']);
            $this->token = $response->decodeResponseJson('token');
            $this->header = ['Authorization' => 'Bearer ' . $this->token, 'Accept' => 'application/json'];
        }
    }

    protected function checkType($value, string $pattern)
    {
        $result = false;
        foreach (explode('|', $pattern) as $type) {
            if($type === gettype($value))
                $result = true;
        }

        $this->assertTrue($result);
    }

    // проверка в обе стороны(чтобы ничего не пропало и не появилось)
    protected function checkStructures(array $structure ,array $item)
    {
        $this->assertIsArray($item);
        foreach ($item as $key => $value) {
            $this->checkType($value, $structure[$key]);
        }
        foreach ($structure as $key => $value) {
            $this->checkType($item[$key],$value);
        }

    }
}
