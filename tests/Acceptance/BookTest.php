<?php
declare(strict_types=1);

namespace Tests\Acceptance;

use Illuminate\Support\Str;
use Tests\Acceptance\Structures\BookStructure;
use Tests\Acceptance\Structures\CategoryStructure;
use Tests\Acceptance\Structures\PublisherStructure;
use Tests\Acceptance\Structures\ValidateErrorStructure;

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
        $response->assertJsonStructure(['*' => BookStructure::$structure]);
        foreach ($response->decodeResponseJson() as $book) {
            $this->checkStructures(BookStructure::$types, $book);
        }
    }

    public function testBookById_success()
    {
        $responseId = $this->get('api/books', $this->header);
        $collection = $responseId->decodeResponseJson();
        $bookId = $collection[0]['id'];// rand
        $response = $this->get('api/books/' . $bookId, $this->header);
        $response->assertStatus(200);
        $response->assertJsonStructure(BookStructure::$structure);
        $this->checkStructures(BookStructure::$types, $response->decodeResponseJson());
    }

    public function testPublishers_success()
    {
        $response = $this->get('api/publishers', $this->header);
        $response->assertStatus(200);
        $response->assertJsonStructure(['*' => PublisherStructure::$structure]);

        foreach ($response->decodeResponseJson() as $publisher) {
            $this->checkStructures(PublisherStructure::$types, $publisher);
        }
    }

    public function testCategories_success()
    {
        $response = $this->get('api/categories', $this->header);
        $response->assertStatus(200);
        $response->assertJsonStructure(['*' => CategoryStructure::$structure]);

        foreach ($response->decodeResponseJson() as $category) {
            $this->checkStructures(CategoryStructure::$types, $category);
        }

    }

    public function testSearch_success()
    {
        $searchString = Str::random(rand(3, 8));
        $response = $this->get('api/search?search=' . $searchString, $this->header);
        $response->assertStatus(200);
        $response->assertJsonStructure(['*' => BookStructure::$structure]);
        foreach ($response->decodeResponseJson() as $book) {
            $this->checkStructures(BookStructure::$types, $book);
        }
    }

    public function testBooks_ByLatitudeAndLongitude_success()
    {
        $latitude = $longitude = 10.10;
        $response = $this->get('api/books?latitude=' . $latitude . '&longitude=' . $longitude, $this->header);
        $response->assertStatus(200);
        $response->assertJsonStructure(['*' => BookStructure::$structure]);
        foreach ($response->decodeResponseJson() as $book) {
            $this->checkStructures(BookStructure::$types, $book);
        }

    }

    public function testBooks_ByLatitudeAndLongitude_failed_noLatitude()
    {
        $longitude = 10.10;
        $response = $this->get('api/books?longitude=' . $longitude, $this->header);
        $response->assertStatus(422);

        $response->assertJsonStructure(ValidateErrorStructure::$structure);
        $this->checkStructures(ValidateErrorStructure::$types, $response->decodeResponseJson());
    }

    public function testBooks_ByLatitudeAndLongitude_failed_noLongitude()
    {
        $latitude = 10.10;
        $response = $this->get('api/books?latitude=' . $latitude, $this->header);
        $response->assertStatus(422);

        $response->assertJsonStructure(ValidateErrorStructure::$structure);
        $this->checkStructures(ValidateErrorStructure::$types, $response->decodeResponseJson());
    }

    public function testBooksBySquare_success()
    {
        $top = $bottom = $left = $right = 10.10;
        $response = $this->get('api/books?square_top=' . $top . '&square_bottom=' . $bottom . '&square_left=' . $left . '&square_right=' . $right, $this->header);
        $response->assertStatus(200);
        $response->assertJsonStructure(['*' => BookStructure::$structure]);

        foreach ($response->decodeResponseJson() as $book) {
            $this->checkStructures(BookStructure::$types, $book);
        }
    }

    public function testBooksBySquare_failed_noBottom()
    {
        $top = $left = $right = 10.10;
        $response = $this->get('api/books?square_top=' . $top . '&square_left=' . $left . '&square_right=' . $right, $this->header);
        $response->assertStatus(422);

        $response->assertJsonStructure(ValidateErrorStructure::$structure);
        $this->checkStructures(ValidateErrorStructure::$types, $response->decodeResponseJson());
    }

    public function testBooksBySquare_failed_noTop()
    {
        $bottom = $left = $right = 10.10;
        $response = $this->get('api/books?&square_bottom=' . $bottom . '&square_left=' . $left . '&square_right=' . $right, $this->header);
        $response->assertStatus(422);

        $response->assertJsonStructure(ValidateErrorStructure::$structure);
        $this->checkStructures(ValidateErrorStructure::$types, $response->decodeResponseJson());
    }

    public function testBooksBySquare_failed_noLeft()
    {
        $top = $bottom = $right = 10.10;
        $response = $this->get('api/books?square_top=' . $top . '&square_bottom=' . $bottom . '&square_right=' . $right, $this->header);
        $response->assertStatus(422);

        $response->assertJsonStructure(ValidateErrorStructure::$structure);
        $this->checkStructures(ValidateErrorStructure::$types, $response->decodeResponseJson());
    }

    public function testBooksBySquare_failed_noRight()
    {
        $top = $bottom = $left = 10.10;
        $response = $this->get('api/books?square_top=' . $top . '&square_bottom=' . $bottom . '&square_left=' . $left, $this->header);
        $response->assertStatus(422);

        $response->assertJsonStructure(ValidateErrorStructure::$structure);
        $this->checkStructures(ValidateErrorStructure::$types, $response->decodeResponseJson());
    }
}
