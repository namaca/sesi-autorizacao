<?php
require 'C://xampp/vendor/autoload.php'; // Certifique-se de que a biblioteca Guzzle está instalada via Composer

use GuzzleHttp\Client;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable('C:\\xampp\htdocs\sesi\env');
$dotenv->load();

$apiKey = $_ENV["SUPABASE_APIKEY"];
$apiKeySecret = $_ENV["SUPABASE_APIKEY2"];

$client = new Client();

$response = $client->request('GET', 'https://gmxeqbapboztlivrkfcy.supabase.co/rest/v1/autorizacao_saida?autorizado=eq.0&select=*', [
    'headers' => [
        'apikey' => $apiKey,
        'Authorization' => "Bearer $apiKeySecret"
    ]
]);

$data = json_decode($response->getBody(), true);
$indexedNames = [];

foreach ($data as $index => $item) {
    $indexedNames[$index + 1] = $item['nomeAluno'];
}

$jsonResult = json_encode($indexedNames);

// Agora, $jsonResult contém o resultado no formato JSON
echo $jsonResult;
