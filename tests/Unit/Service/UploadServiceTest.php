<?php

namespace Tests\Unit\Service;

use App\Service\UploadService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UploadServiceTest extends TestCase
{
    /**
     * @var UploadService
     */
    protected $service;

    /**
     * Setup
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(UploadService::class);
    }

    /**
     * Test upload image success
     *
     * @return void
     */
    public function testUploadImageSuccess()
    {
        Storage::shouldReceive('disk->exists')
            ->andReturn(false);
        Storage::shouldReceive('disk->putFileAs');
        Storage::shouldReceive('disk->url')
            ->andReturn('http://bucket-disk.com/test/image.png');
        $uploadedFile = $this->createMock(UploadedFile::class);
        $uploadedFile->method('getClientOriginalName')
            ->willReturn('image.png');
        $service = $this->service;
        $setDisk = $service->disk('public');
        $setFileName = $setDisk->as('image');
        $setExtension = $setFileName->extension('png');
        $upload = $setExtension->upload($uploadedFile, '/test');

        $this->assertInstanceOf(UploadService::class, $setDisk);
        $this->assertInstanceOf(UploadService::class, $setFileName);
        $this->assertInstanceOf(UploadService::class, $setExtension);
        $this->assertTrue(is_array($upload));
        $this->assertEquals([
            'file' => 'image.png',
            'url' => 'http://bucket-disk.com/test/image.png'
        ], $upload);
    }
}
