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

class CategoryApiTest extends TestCase
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

    public function testStoreNewCategory(): void
    {
        $response = $this->actingAs($this->user)
            ->postJson('/api/categories', [
                'name' => $categoryName = 'Some category',
            ]);

        $response->assertStatus(201);

        $response->assertJson(fn(AssertableJson $json) => $json
            ->where('data.name', $categoryName)
            ->where('data.slug', 'some-category')
            ->etc()
        );
        $this->assertDatabaseHas('categories', [
            'name' => $categoryName,
        ]);
    }

    public function testCategoryUpdate(): void
    {
        $this->actingAs($this->user)
            ->postJson('/api/categories', [
                'name' => 'Some category',
            ])->assertStatus(201);

        $updatedCategoryName = 'Updated Category';
        $response = $this->actingAs($this->user)
            ->patchJson("/api/categories/some-category", [
                'name' => $updatedCategoryName,
            ]);

        $response->assertStatus(200);
        $response->assertJson(fn(AssertableJson $json) => $json
            ->where('data.name', $updatedCategoryName)
            ->where('data.slug', 'some-category'));

        $this->assertDatabaseHas('categories', [
            'name' => $updatedCategoryName,
        ]);
    }

    public function testAddCategoryToImage(): void
    {
        // create a new image
        $response = $this->actingAs($this->user)
            ->postJson('/api/images', [
                'name' => Factory::create()->word,
                'image' => UploadedFile::fake()->image('test.png'),
            ]);
        $response->assertStatus(201);
        $imageId = $response->json('data.id');

        // create a new category
        $response = $this->actingAs($this->user)
            ->postJson('/api/categories', [
                'name' => $categoryName = 'Some category',
            ]);
        $response->assertStatus(201);
        $categoryId = $response->json('data.id');

        // add category to an image
        $response = $this->actingAs($this->user)
            ->postJson("/api/images/$imageId/add-category/", [
                'category_id' => $categoryId
            ]);
        $response->assertStatus(200);
        $response->assertJson(fn(AssertableJson $json) => $json
            ->has('data.categories', fn(AssertableJson $json) => $json->where('0.name', $categoryName)));
    }
}
