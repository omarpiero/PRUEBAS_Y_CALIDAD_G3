<?php

namespace App\Services;

class VideoEmbedService
{
    /**
     * Get the embed url for YouTube or Vimeo URLs.
     *
     * @param string $source (youtube, vimeo, upload)
     * @param string $url
     * @return string|null
     */
    public function getEmbedUrl(string $source, string $url): ?string
    {
        if ($source === 'youtube') {
            $pattern = '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/|youtube\.com\/shorts\/)([^"&?\/ ]{11})/i';
            if (preg_match($pattern, $url, $matches)) {
                return 'https://www.youtube.com/embed/' . $matches[1];
            }
        } elseif ($source === 'vimeo') {
            $pattern = '/(?:vimeo\.com\/(?:channels\/[^\/]+\/|groups\/[^\/]+\/video\/|album\/[^\/]+\/video\/|showcase\/[^\/]+\/video\/)?|player\.vimeo\.com\/video\/)(\d+)/i';
            if (preg_match($pattern, $url, $matches)) {
                return 'https://player.vimeo.com/video/' . $matches[1];
            }
        } elseif ($source === 'upload') {
            return $url;
        }

        return null;
    }
}
