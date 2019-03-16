<?php

namespace Tests\Acceptance;

use App\Models\Category;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/** @group Acceptance */
class BookTest extends TestCase
{

    public function setUp($auth = false): void
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

    public function testBooks_ByLatitudeAndLongitude_success()
    {
        $latitude = $longitude = 10.10;
        $response = $this->get('api/books?latitude=' . $latitude . '&longitude=' . $longitude);
        $response->assertStatus(200);

    }

    public function testBooks_ByLatitudeAndLongitude_failed_noLatitude()
    {
        $longitude = 10.10;
        $response = $this->get('api/books?longitude=' . $longitude, $this->header);
        $response->assertStatus(422);
    }

    public function testBooks_ByLatitudeAndLongitude_failed_noLongitude()
    {
        $latitude = 10.10;
        $response = $this->get('api/books?latitude=' . $latitude, $this->header);
        $response->assertStatus(422);
    }

    public function testBooksBySquare_success()
    {
        $top = $bottom = $left = $right = 10.10;
        $response = $this->get('api/books?square_top=' . $top . '&square_bottom=' . $bottom . '&square_left=' . $left . '&square_right=' . $right, $this->header);
        $response->assertStatus(200);
    }

    public function testBooksBySquare_failed_noBottom()
    {
        $top = $left = $right = 10.10;
        $response = $this->get('api/books?square_top=' . $top . '&square_left=' . $left . '&square_right=' . $right, $this->header);
        $response->assertStatus(422);
    }

    public function testBooksBySquare_failed_noTop()
    {
        $bottom = $left = $right = 10.10;
        $response = $this->get('api/books?&square_bottom=' . $bottom . '&square_left=' . $left . '&square_right=' . $right, $this->header);
        $response->assertStatus(422);
    }

    public function testBooksBySquare_failed_noLeft()
    {
        $top = $bottom = $right = 10.10;
        $response = $this->get('api/books?square_top=' . $top . '&square_bottom=' . $bottom . '&square_right=' . $right, $this->header);
        $response->assertStatus(422);
    }

    public function testBooksBySquare_failed_noRight()
    {
        $top = $bottom = $left = 10.10;
        $response = $this->get('api/books?square_top=' . $top . '&square_bottom=' . $bottom . '&square_left=' . $left, $this->header);
        $response->assertStatus(422);
    }
}