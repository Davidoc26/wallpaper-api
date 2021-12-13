<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\ImageService;
use App\Services\Uploader\ImageUploader;
use Faker\Factory;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Testing\Fluent\AssertableJson;
use Storage;
use Tests\TestCase;

class ImageApiTest extends TestCase
{
    use DatabaseTransactions;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $storage = Storage::fake('images');
        $this->app->when([ImageUploader::class, ImageService::class])
            ->needs(Filesystem::class)
            ->give(function () use ($storage): Filesystem {
                return $storage;
            });
        $this->user = User::factory()->create();
    }

    public function testImageUploaded(): void
    {
        $response = $this->actingAs($this->user)
            ->postJson('/api/images', [
                'name' => $name = Factory::create()->word,
                'image' => UploadedFile::fake()->image('test.png'),
            ]);

        $response->assertStatus(201);
        $this->assertCount(1, Storage::disk('images')->allFiles());
        $this->assertDatabaseHas('images', [
            'name' => $name,
        ]);
    }

    public function testManyImagesUploaded(): void
    {
        $response = $this->actingAs($this->user)
            ->post('/api/images/upload-all', [
                'images' => [
                    [
                        'image' => UploadedFile::fake()->image('test.png'),
                        'name' => $imgName1 = Factory::create()->word,
                    ],
                    [
                        'image' => UploadedFile::fake()->image('test2.png'),
                        'name' => $imgName2 = Factory::create()->word,
                    ],
                ],
            ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('images', [
            'name' => $imgName1,
        ]);
        $this->assertDatabaseHas('images', [
            'name' => $imgName2,
        ]);
        $this->assertCount(2, Storage::disk('images')->allFiles());
    }

    public function testImageDownload(): void
    {
        // create image for downloading
        $response = $this->actingAs($this->user)
            ->postJson('/api/images', [
                'name' => Factory::create()->word,
                'image' => UploadedFile::fake()->image('test.png'),
            ]);
        $response->assertStatus(201);
        $imageId = $response->json('data.id');

        $response = $this->getJson("/api/images/$imageId/download");
        $response->assertHeader('Content-Type', 'image/png');
    }

    public function testImageShow(): void
    {
        $response = $this->actingAs($this->user)
            ->postJson('/api/images', [
                'name' => $imageName = Factory::create()->word,
                'image' => UploadedFile::fake()->image('test.png'),
            ]);

        $response->assertStatus(201);
        $imageId = $response->json('data.id');

        $response = $this->actingAs($this->user)
            ->getJson("/api/images/$imageId");

        $response->assertJson(fn(AssertableJson $json) => $json
            ->where('data.id', $imageId)
            ->where('data.name', $imageName)
            ->has('data.path')
            ->has('data.created_at')
        );
    }
}
