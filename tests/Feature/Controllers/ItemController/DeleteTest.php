<?php

namespace Tests\Feature\Controller\ItemController;

use App\Models\Colour;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_delete_deletes_item(): void
    {
        $item = Item::factory()->create(['user_id' => $this->user->id]);
        $colours = Colour::factory()->count(4)->create(['item_id' => $item->id]);

        $response = $this->actingAs($this->user)
            ->withHeader('Accept', 'application/json')
            ->delete(route('items.destroy', $item->id));

        $response->assertOk();
        $content = $response->getContent();
        $this->assertEquals('success', $content);
        $itemUpdated = Item::find($item->id);

        $this->assertDatabaseMissing('items', [
            'id' => $item->id,
        ]);

        $item->colours()->each(function ($colour) {
            $this->assertDatabaseMissing('colours', [
                'id' => $colour->id,
            ]);
        });
    }

    public function test_delete_fails_with_item_not_exist(): void
    {
        $response = $this->actingAs($this->user)
            ->withHeader('Accept', 'application/json')
            ->delete(route('items.destroy', 999999));

        $response->assertJsonValidationErrors(['id']);
        $response->assertStatus(422);
    }
}
