<?php

namespace Tests\Feature\Controller\ItemController;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_store_saves_item(): void
    {
        $payload = [
            'title' => Str::random(10),
            'description' => Str::random(20),
            'colours' => [
                '#123456',
                '#234567',
                '#345678',
                '#456789',
            ],
        ];

        $response = $this->actingAs($this->user)
            ->withHeader('Accept', 'application/json')
            ->post(route('items.store'), $payload);

        $response->assertStatus(200);

        $savedItem = Item::with(['colours'])->where('user_id', $this->user->id)->first();

        $this->assertEquals($this->user->id, $savedItem->user_id);
        $this->assertEquals($payload['title'], $savedItem->title);
        $this->assertEquals($payload['description'], $savedItem->description);

        foreach ($savedItem->colours as $i => $colour) {
            $this->assertEquals($payload['colours'][$i], $colour->colour);
        }
    }

    public function test_store_allows_null_title_description(): void
    {
        $payload = [
            'colours' => ['#123456', '#234567', '#345678', '#456789'],
        ];

        $response = $this->actingAs($this->user)
            ->withHeader('Accept', 'application/json')
            ->post(route('items.store'), $payload);

        $response->assertStatus(200);

        $savedItem = Item::with(['colours'])->where('user_id', $this->user->id)->first();

        $this->assertEquals($this->user->id, $savedItem->user_id);
        $this->assertEquals('', $savedItem->title);
        $this->assertEquals('', $savedItem->description);

        foreach ($savedItem->colours as $i => $colour) {
            $this->assertEquals($payload['colours'][$i], $colour->colour);
        }
    }

    /**
     * @dataProvider provides_invalid_store_payload
     */
    public function test_store_validates_input($payload, $invalidKeys)
    {
        $response = $this->actingAs($this->user)
            ->withHeader('Accept', 'application/json')
            ->post(route('items.store'), $payload);

        $response->assertJsonValidationErrors($invalidKeys);
        $response->assertStatus(422);
    }

    public function provides_invalid_store_payload()
    {
        return [
            'too long title' => [
                [
                    'title' => Str::random(101),
                    'description' => 'test title',
                    'colours' => ['#123456', '#234567', '#345678', '#456789'],
                ], ['title'],
            ],
            'too long description' => [
                [
                    'title' => Str::random(10),
                    'description' => Str::random(300),
                    'colours' => ['#123456', '#234567', '#345678', '#456789'],
                ], ['description'],
            ],
            'missing colour' => [
                [
                    'title' => Str::random(10),
                    'description' => Str::random(100),
                ], ['colours'],
            ],
            'invalid colour code' => [
                [
                    'title' => Str::random(10),
                    'description' => Str::random(100),
                    'colours' => ['#12', '234567', '#345', '#456789'],
                ], ['colours.0', 'colours.1', 'colours.2'],
            ],
        ];
    }
}
