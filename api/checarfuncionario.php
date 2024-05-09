<?php
require 'C://xampp/vendor/autoload.php'; // Certifique-se de que a biblioteca Guzzle está instalada via Composer

use GuzzleHttp\Client;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable('C:\\xampp\htdocs\sesi\env');
$dotenv->load();

$apiKey = $_ENV["SUPABASE_APIKEY"];
$apiKeySecret = $_ENV["SUPABASE_APIKEY2"];

$id = $_GET['id'];

if (preg_match('/[^0-9]/', $id)) {
    echo "[]";
    exit;
} 
$client = new Client();

$response = $client->request('GET', "https://gmxeqbapboztlivrkfcy.supabase.co/rest/v1/funcionarios?IDfuncionario=eq.$id&select=*", [
    'headers' => [
        'apikey' => $apiKey,
        'Authorization' => "Bearer $apiKeySecret"
    ]
]);

// Verifique o código de status da resposta

    if ($response->getStatusCode() == 200) {
        $data = json_decode($response->getBody(), true);
    
        // Verifique se o JSON contém dados
        if (!empty($data)) {
            // Faça algo com os dados aqui
            echo json_encode(["res" => 200]);
        } else {
            echo "[]";
        }
    } else {
        echo "[]";
    }


?>
