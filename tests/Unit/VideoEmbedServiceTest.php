<?php

namespace Tests\Unit;

use App\Services\VideoEmbedService;
use Tests\TestCase;

class VideoEmbedServiceTest extends TestCase
{
    private VideoEmbedService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new VideoEmbedService();
    }

    public function test_youtube_url_conversion()
    {
        $urls = [
            'https://www.youtube.com/watch?v=dQw4w9WgXcQ' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
            'https://youtu.be/dQw4w9WgXcQ' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
            'https://www.youtube.com/embed/dQw4w9WgXcQ' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
            'https://youtube.com/shorts/dQw4w9WgXcQ' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
        ];

        foreach ($urls as $input => $expected) {
            $this->assertEquals($expected, $this->service->getEmbedUrl('youtube', $input));
        }
    }

    public function test_vimeo_url_conversion()
    {
        $urls = [
            'https://vimeo.com/123456789' => 'https://player.vimeo.com/video/123456789',
            'https://player.vimeo.com/video/123456789' => 'https://player.vimeo.com/video/123456789',
        ];

        foreach ($urls as $input => $expected) {
            $this->assertEquals($expected, $this->service->getEmbedUrl('vimeo', $input));
        }
    }

    public function test_upload_returns_input_directly()
    {
        $path = 'materials/1/1/video.mp4';
        $this->assertEquals($path, $this->service->getEmbedUrl('upload', $path));
    }

    public function test_invalid_urls_return_null()
    {
        $this->assertNull($this->service->getEmbedUrl('youtube', 'https://google.com'));
        $this->assertNull($this->service->getEmbedUrl('vimeo', 'https://youtube.com/watch?v=dQw4w9WgXcQ'));
        $this->assertNull($this->service->getEmbedUrl('youtube', 'not-a-url'));
    }
}
