<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class EmailImageUploadTest extends TestCase
{
    use RefreshDatabase;

    protected function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    public function test_it_stores_an_image_and_returns_an_absolute_url(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->admin())
            ->postJson(route('admin.email-images.store'), [
                'file' => UploadedFile::fake()->image('banner.jpg', 600, 300),
            ]);

        $response->assertOk()->assertJsonStructure(['location']);

        $location = $response->json('location');

        // An inbox has no page to resolve a relative path against.
        $this->assertStringStartsWith('http', $location);
        $this->assertCount(1, Storage::disk('public')->files('email-images'));
    }

    public function test_it_rejects_a_non_image_with_a_message_tinymce_can_show(): void
    {
        Storage::fake('public');

        $this->actingAs($this->admin())
            ->postJson(route('admin.email-images.store'), [
                'file' => UploadedFile::fake()->create('invoice.pdf', 10, 'application/pdf'),
            ])
            ->assertStatus(422)
            ->assertJsonStructure(['message']);

        $this->assertEmpty(Storage::disk('public')->files('email-images'));
    }

    public function test_guests_cannot_upload(): void
    {
        $this->postJson(route('admin.email-images.store'), [
            'file' => UploadedFile::fake()->image('x.jpg'),
        ])->assertUnauthorized();
    }
}
