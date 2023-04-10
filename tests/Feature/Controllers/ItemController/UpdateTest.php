<?php

namespace Tests\Feature\Controller\ItemController;

use App\Models\Colour;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_update_updates_item(): void
    {
        $item = Item::factory()->create(['user_id' => $this->user->id]);
        $colours = Colour::factory()->count(4)->create(['item_id' => $item->id]);

        $payload = [
            'title' => Str::random(20),
            'description' => Str::random(100),
        ];

        $response = $this->actingAs($this->user)
            ->withHeader('Accept', 'application/json')
            ->put(route('items.update', $item->id), $payload);

        $response->assertOk();
        $content = $response->getContent();
        $this->assertEquals('success', $content);
        $itemUpdated = Item::find($item->id);

        $this->assertEquals($payload['title'], $itemUpdated->title);
        $this->assertEquals($payload['description'], $itemUpdated->description);
    }

    public function test_update_allows_empty_title_description(): void
    {
        $item = Item::factory()->create(['user_id' => $this->user->id]);
        $colours = Colour::factory()->count(4)->create(['item_id' => $item->id]);

        $payload = [];

        $response = $this->actingAs($this->user)
            ->withHeader('Accept', 'application/json')
            ->put(route('items.update', $item->id), $payload);

        $response->assertOk();
        $content = $response->getContent();
        $this->assertEquals('success', $content);
        $itemUpdated = Item::find($item->id);

        $this->assertEquals('', $itemUpdated->title);
        $this->assertEquals('', $itemUpdated->description);
    }

    public function test_update_fails_with_non_exists_item(): void
    {
        $payload = [];

        $response = $this->actingAs($this->user)
            ->withHeader('Accept', 'application/json')
            ->put(route('items.update', 99999), $payload);

        $response->assertJsonValidationErrors(['id']);
        $response->assertStatus(422);
    }

    /**
     * @dataProvider provides_invalid_update_payload
     */
    public function test_update_validates_input($payload, $invalidKeys)
    {
        $item = Item::factory()->create(['user_id' => $this->user->id]);
        $response = $this->actingAs($this->user)
            ->withHeader('Accept', 'application/json')
            ->put(route('items.update', $item->id), $payload);

        $response->assertJsonValidationErrors($invalidKeys);
        $response->assertStatus(422);
    }

    public function provides_invalid_update_payload()
    {
        return [
            'too long title' => [
                [
                    'title' => Str::random(2000),
                    'description' => 'test title',
                ], ['title'],
            ],
            'too long description' => [
                [
                    'title' => Str::random(10),
                    'description' => Str::random(500),
                ], ['description'],
            ],
        ];
    }
}
