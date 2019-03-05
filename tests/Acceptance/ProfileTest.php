<?php

namespace Tests\Acceptance;

use Illuminate\Support\Str;

/** @group Acceptance */
class ProfileTest extends TestCase
{

    public function setUp($auth = false)
    {
        parent::setUp(true);
    }

    public function testGetProfile_success()
    {
        $request = $this->get('profile', $this->header);
        $request->assertStatus(200);
    }

    public function testUpdateNameProfile_success()
    {
        $name = Str::random();
        $request = $this->post('profile', ['name' => $name], $this->header);
        $request->assertStatus(200);
        $this->assertDatabaseHas('users', ['email' => 'admin@findbook.info', 'name' => $name]);
    }

    public function testGetProfileInventory()
    {
        $request = $this->get('profile/inventory', $this->header);
        $request->assertStatus(200);
    }

    public function testGetProfileArchive_success()
    {
        $request = $this->get('profile/inventory/archive', $this->header);
        $request->assertStatus(200);
    }

    public function testGetProfileFavorite_success()
    {
        $request = $this->get('profile/inventory/favorite',$this->header);
        $request->assertStatus(200);
    }

}