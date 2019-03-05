<?php

namespace Tests\Acceptance;

use Illuminate\Support\Str;

/** @group Acceptance */
class BookTest extends TestCase
{

    public function setUp($auth = false)
    {
        parent::setUp(true);
    }

    public function testBooks_success()
    {
        $request = $this->get('books', $this->header);
        $request->assertStatus(200);
    }

    public function testBookById_success()
    {
        $request = $this->get('books', $this->header);
        $collection = $request->decodeResponseJson();
        $bookId = $collection->first()->value('id');
        $request = $this->get('books/' . $bookId, $this->header);
        $request->assertStatus(200);

    }

    public function testPublishers_success()
    {
        $request = $this->get('publishers', $this->header);
        $request->assertStatus(200);
    }

    public function testCategories_success()
    {
        $request = $this->get('categories', $this->header);
        $request->assertStatus(200);
    }

    public function testSearch_success()
    {
        $searchString = Str::random(rand(3, 8));
        $request = $this->get('search?search=' . $searchString, $this->header);
        $request->assertStatus(200);
    }
}