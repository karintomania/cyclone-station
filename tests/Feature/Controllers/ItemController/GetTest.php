<?php

namespace Tests\Feature\Controller\ItemController;

use App\Models\Colour;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /**
     * Test GET /items.
     */
    public function test_get_returns_items_list(): void
    {
        $item = Item::factory()->create(['user_id' => $this->user->id]);
        $colours = Colour::factory()->count(4)->create(['item_id' => $item->id]);

        $response = $this->actingAs($this->user)->get(route('items.index'));

        $response->assertStatus(200);

        $responseItem = json_decode($response->getContent(), true)['data'][0];

        $this->assertEquals($item->user_id, $responseItem['user_id']);
        $this->assertEquals($item->title, $responseItem['title']);
        $this->assertEquals($item->description, $responseItem['description']);

        $responseColours = $responseItem['colours'];
        $colours->each(function ($colour, $i) use ($responseColours) {
            $this->assertEquals($colour->colour, $responseColours[$i]);
        });
    }
}
