<?php

namespace App\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use MongoDB\Driver\Exception\ExecutionTimeoutException;

class TapMusicService
{
    private string $baseUri = "https://tapmusic.net/";
    private Client $client;
    private array $queryParams;

    public function __construct(
        private readonly string $username,
    )
    {
       $this->queryParams = [
           'headers' => [
                'Content-Type' => 'image/jpeg'
           ]
       ];

        $this->client = new Client(['base_uri' => $this->baseUri]);
    }

    /**
     * @throws GuzzleException
     * @throws \Exception
     */
    public function generate(): string
    {
        $uri = sprintf("collage.php?user=%s&type=7day&size=3x3&caption=true", $this->username);

        $response = $this->client->request('GET', $uri, ['stream' => true]);

        $body = $response->getBody()->getContents();
        $base64 = base64_encode($body);
        $mime = "image/png";
        $img = ('data:' . $mime . ';base64,' . $base64);

        $name = time() . '.png';

        $check = file_put_contents($name, $body);

        if(!$check) {
            echo "<img src=$img alt='ok'>";
            die();
        }
        //echo "<img src=$img alt='ok'>";

        return $name;

    }
}