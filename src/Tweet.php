<?php

namespace App;

use App\Service\TapMusicService;
use DG\Twitter\Exception;
use DG\Twitter\Twitter;
use Dotenv\Dotenv;
use GuzzleHttp\Exception\GuzzleException;

class Tweet
{
    private Twitter $twitter;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->twitter = new Twitter($_ENV['CONSUMER_KEY'], $_ENV['CONSUMER_SECRET'], $_ENV['OAUTH_ACCESS_TOKEN'], $_ENV['OAUTH_ACCESS_TOKEN_SECRET']);

    }
    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function check(): void
    {
        $mentions = $this->twitter->request('statuses/mentions_timeline', 'GET', ['count' => 1]);

        foreach ($mentions as $mention) {

            var_dump($mention);

            $userLastFm = str_replace("@lastfm_week ", "", $mention->text);
            $tapMusic = new TapMusicService($userLastFm);
            $fileName = $tapMusic->generate();

            $res = $this->twitter->request(
                'https://upload.twitter.com/1.1/media/upload.json',
                'POST',
                [],
                ['media' =>  $fileName]
            );

            $this->twitter->request('statuses/update','POST', [
                'in_reply_to_status_id' => $mention->id_str,
                'media_ids' => $res->media_id_string,
                'status' => '@' . $mention->user->screen_name . ' ta na mao'
                ]);
        }
    }
}