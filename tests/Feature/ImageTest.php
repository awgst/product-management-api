<?php

namespace Tests\Feature;

use App\Models\Image;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ImageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test get all images success.
     *
     * @return void
     */
    public function testGetAllImagesSuccess()
    {
        $response = $this->get('/api/v1/image');

        $response->assertStatus(200);
    }

    /**
     * Test get image by id success.
     *
     * @return void
     */
    public function testGetImageByIdSuccess()
    {
        $image = Image::factory()->create(['enable' => true]);
        $response = $this->get('/api/v1/image/' . $image->id);

        $response->assertStatus(200);
    }

    /**
     * Test create image success.
     *
     * @return void
     */
    public function testCreateImageSuccess()
    {
        $data = [
            'name' => 'Image 1',
            'file' => UploadedFile::fake()->image('image.jpg')
        ];
        $response = $this->post('/api/v1/image', $data);

        $response->assertStatus(200);
    }

    /**
     * Test update image success.
     * 
     * @return void
     */
    public function testUpdateImageSuccess()
    {
        $image = Image::factory()->create(['enable' => true]);
        $data = [
            'name' => 'Image 1',
            'file' => UploadedFile::fake()->image('image.jpg')
        ];
        $response = $this->put('/api/v1/image/' . $image->id, $data);

        $response->assertStatus(200);
    }

    /**
     * Test update image will fail if image is not found.
     * 
     * @return void
     */
    public function testUpdateImageWillFailIfImageIsNotFound()
    {
        $data = [
            'name' => 'Image 1',
            'file' => UploadedFile::fake()->image('image.jpg')
        ];
        $response = $this->put('/api/v1/image/1', $data);

        $response->assertStatus(404);
    }

    /**
     * Test delete image success.
     * 
     * @return void
     */
    public function testDeleteImageSuccess()
    {
        $image = Image::factory()->create(['enable' => true]);
        $response = $this->delete('/api/v1/image/' . $image->id);

        $response->assertStatus(200);
    }

    /**
     * Test delete image will fail if image is not found.
     * 
     * @return void
     */
    public function testDeleteImageWillFailIfImageIsNotFound()
    {
        $response = $this->delete('/api/v1/image/1');

        $response->assertStatus(404);
    }
}
