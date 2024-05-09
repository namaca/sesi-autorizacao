<?php
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: registro.php");
    exit;
}

require 'C://xampp/vendor/autoload.php'; // Certifique-se de que a biblioteca Guzzle está instalada via Composer

use GuzzleHttp\Client;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable('C:\\xampp\htdocs\sesi\env');
$dotenv->load();

$apiKey = $_ENV["SUPABASE_APIKEY"];

$client = new Client();

$response = $client->request('GET', 'https://gmxeqbapboztlivrkfcy.supabase.co/rest/v1/alunos?select=*', [
    'headers' => [
        'apikey' => $apiKey,
        'Authorization' => "Bearer $apiKey",
    ]
]);

echo $response->getBody();
?>