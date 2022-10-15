<?php

namespace App\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class TapMusicService
{
    private string $baseUri = "https://tapmusic.net/collage.php?user=";
    private Request $client;

    public function __construct(
        private readonly string $username
    )
    {
        $this->client = new Request('GET', $this->baseUri, [
            'user' => $this->username
        ]);

    }

    public function generate()
    {
    }
}