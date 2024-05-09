<?php

require 'C://xampp/vendor/autoload.php'; // Certifique-se de que a biblioteca Guzzle está instalada via Composer

 use GuzzleHttp\Client;
 use Dotenv\Dotenv;

 $dotenv = Dotenv::createImmutable('C:\\xampp\htdocs\sesi\env');
 $dotenv->load();
 $client = new Client();

 $apiKey = $_ENV["SUPABASE_APIKEY"];

 function validateAccess() {
    $headers = getallheaders();
  
    if (isset($headers['Authorization'])) {

        // Verifica se o cabeçalho começa com 'Bearer '
        if (strpos($headers['Authorization'], 'Bearer') === 0) {
            // Remove 'Bearer ' do cabeçalho para obter o token
            $token = substr($headers['Authorization'], 7);
    
            // Faça sua lógica de validação do token aqui
            $validKey = $_ENV["BEARER_CODE"];
            if ($token === $validKey) {
                return true;
            } else {
                header('HTTP/1.1 401 Unauthorized');
                echo "Token inválido.";
                exit();
            }
        } else {
            header('HTTP/1.1 401 Unauthorized');
            echo "Esperado token Bearer.";
            exit();
        }
    } else {
        header('HTTP/1.1 401 Unauthorized');
        echo "Token de autorização não fornecido.";
        exit();
    }
    
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    validateAccess();

    if(!isset($_POST["name"])){
        echo '{"msg": "parametro name não informado"}';
        exit();
    }
    if(!isset($_POST["tipo"])){
        echo '{"msg": "parametro tipo não informado"}';
        exit();
    }
    if(!isset($_POST["token"])){
        echo '{"msg": "parametro token não informado"}';
        exit();
    }

    $nome = $_POST["name"];
    $tipo = $_POST["tipo"];
    $token = $_POST["token"];


    // $data = array(
    //     'nome' => $nome,
    //     'status' => 0
    // );

    try {
        $queryaa = $client->request('GET', "https://gmxeqbapboztlivrkfcy.supabase.co/rest/v1/tokens?select=*", [
            'headers' => [
                'apikey' => $apiKey,
                'Authorization' => "Bearer $apiKey",
            ]
        ]);

        $dataaaa = json_decode($queryaa->getBody(), true);

        $total = count($dataaaa);

        $response = $client->request('POST', 'https://gmxeqbapboztlivrkfcy.supabase.co/rest/v1/tokens', [
            'headers' => [
                'apikey' => $apiKey,
                'Authorization' => "Bearer $apiKey",
                'Content-Type' => 'application/json',
                'Prefer' => 'return=minimal'
            ],
            'json' => [
                'id' => $total+1,
                'token' => $token,
                'created_at' => null,
                'tipo' => $tipo,
                'nomeAluno' => $nome
            ]
        ]);
    } catch (Exception $e){
        echo '{"error": "Erro na atualizacão da supabase"}';
        exit();
    }
    
    
    //file_put_contents("../env/temp_{$token}.json", json_encode($data));



   // echo file_get_contents("../env/temp_{$token}.json");
   echo '{"status": "200", "msg": "Atualizado com sucesso"}';
} elseif ($_SERVER["REQUEST_METHOD"] == "GET") {

    validateAccess();

    if(isset($_GET["token"])){

        $token2 = $_GET["token"];

        if (file_exists("../env/temp_{$token2}.json")) {
            // Carrega o conteúdo do arquivo JSON
            $json_data = file_get_contents("../env/temp_{$token2}.json");
        
            // Decodifica o JSON em um array associativo em PHP
            $data = json_decode($json_data, true);
        
            // Verifica se a decodificação foi bem-sucedida
            if ($data !== null) {

                if($data["status"] !== 0){
                    echo '{"msg": "Autorizado"}';
                    unlink("../env/temp_{$token2}.json");
                    unlink("../env/auth_{$token2}.txt");
                    
                    
                    $deletarToken = $client->request('DELETE', "https://gmxeqbapboztlivrkfcy.supabase.co/rest/v1/tokens?token=eq.$token2", [
                        'headers' => [
                            'apikey' => $apiKey,
                            'Authorization' => "Bearer $apiKey",
                        ]
                    ]);

                } else {
                    echo '{"msg": "Aguardando autorização"}';
                }
                
            } else {
                echo '{"msg": "erro na decodificacao do JSON"}';
                exit();
            }
        } else {
            echo '{"msg": "JSON não existe"}';
            exit();
        }

    } else {
        echo '{"msg": "Token não informado"}';
        exit();
    }
    
    
}
?>
