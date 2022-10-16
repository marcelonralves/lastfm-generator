<?php
require_once __DIR__ . "/vendor/autoload.php";

use App\Tweet;
use GuzzleHttp\Exception\GuzzleException;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$twitter = new Tweet();

try {
    $twitter->check();
} catch (\DG\Twitter\Exception|GuzzleException $e) {
    echo $e->getMessage();
}