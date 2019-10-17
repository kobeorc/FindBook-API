<?php
declare(strict_types=1);

namespace Tests\Acceptance;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Tests\Acceptance\Structures\BookStructure;
use Tests\Acceptance\Structures\UserStructure;

/** @group Acceptance */
class ProfileTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp($auth = false):void
    {
        parent::setUp(true);
    }

    /** @url api/profile */
    public function testGetProfile_success()
    {
        $response = $this->get('api/profile', $this->header);
        $response->assertStatus(200);
        $response->assertJsonStructure(UserStructure::$structure);
        $this->checkStructures(UserStructure::$types, $response->decodeResponseJson());
    }

    public function testUpdateNameProfile_success()
    {
        $name = Str::random();
        $response = $this->post('api/profile', ['name' => $name], $this->header);
        $response->assertStatus(200);
        $response->assertJsonStructure(UserStructure::$structure);
        $this->assertDatabaseHas('users', ['email' => $this->testUserEmail, 'name' => $name]);
        $this->checkStructures(UserStructure::$types, $response->decodeResponseJson());
    }

    /** @url api/profile/inventory */
    public function testGetProfileInventory()
    {
        $response = $this->get('api/profile/inventory', $this->header);
        $response->assertStatus(200);
        $response->assertJsonStructure(['*'=>BookStructure::$structure]);
        foreach ($response->decodeResponseJson() as $book) {
            $this->checkStructures(BookStructure::$types, $book);
        }
    }

    /** @url api/profile/inventory/archive */
    public function testGetProfileArchive_success()
    {
        $response = $this->get('api/profile/inventory/archive', $this->header);
        $response->assertStatus(200);
        $response->assertJsonStructure(['*'=>BookStructure::$structure]);
        foreach ($response->decodeResponseJson() as $book) {
            $this->checkStructures(BookStructure::$types, $book);
        }
    }

    /** @url api/profile/inventory/favorite */
    public function testGetProfileFavorite_success()
    {
        $response = $this->get('api/profile/inventory/favorite',$this->header);
        $response->assertStatus(200);
        $response->assertJsonStructure(['*'=>BookStructure::$structure]);
        foreach ($response->decodeResponseJson() as $book) {
            $this->checkStructures(BookStructure::$types, $book);
        }
    }

}
