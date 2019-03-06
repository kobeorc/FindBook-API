<?php

namespace Tests\Feature\Acceptance;

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
        $response = $this->get('api/books', $this->header);
        $response->assertStatus(200);
    }

    public function testBookById_success()
    {
        $responseId = $this->get('api/books', $this->header);
        $collection = $responseId->decodeResponseJson();
        $bookId = $collection[0]['id'];
        $response = $this->get('api/books/' . $bookId, $this->header);
        $response->assertStatus(200);
    }

    public function testPublishers_success()
    {
        $request = $this->get('api/publishers', $this->header);
        $request->assertStatus(200);
    }

    public function testCategories_success()
    {
        $request = $this->get('api/categories', $this->header);
        $request->assertStatus(200);
    }

    public function testSearch_success()
    {
        $searchString = Str::random(rand(3, 8));
        $request = $this->get('api/search?search=' . $searchString, $this->header);
        $request->assertStatus(200);
    }
}