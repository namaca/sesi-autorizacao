<?php
session_start();

// Verifica se o usuário está logado
if ((!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) && ($_SESSION["user_type"] == 'admin')) {
    header("location: registro.php");
    exit;
}
    require 'C://xampp/vendor/autoload.php'; // Certifique-se de que a biblioteca Guzzle está instalada via Composer



    use GuzzleHttp\Client;
    use Dotenv\Dotenv;

    $client = new Client();


    $dotenv = Dotenv::createImmutable('C:\\xampp\htdocs\sesi\env');
    $dotenv->load();

    $apiKey = $_ENV["SUPABASE_APIKEY"];
    $apiKeySecret = $_ENV["SUPABASE_APIKEY2"];

    $apiKey = $_ENV["SUPABASE_APIKEY"];
    $host = $_ENV["POSTGRE_HOST"];
    $db = $_ENV["POSTGRE_DB"];
    $user = $_ENV["POSTGRE_USER"];
    $pwd = $_ENV["POSTGRE_PASSWORD"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = date('Y-m-d H:i:s', time());


    if ($_POST['action'] == 'autorizar') {
        // Código para autorizar aluno (SQL UPDATE)
        $id = $_POST['id'];
        $aluno = $_POST['aluno'];
        // Aqui você faz a conexão com o banco de dados e executa a query de atualização
        // Prepare statement
        $nome_formatado = urlencode($aluno);

        $response = $client->request('PATCH', "https://gmxeqbapboztlivrkfcy.supabase.co/rest/v1/autorizacao_saida?nomeAluno=eq.$nome_formatado", [
            'headers' => [
                'apikey' => $apiKey,
                'Authorization' => "Bearer $apiKeySecret",
                'Content-Type' => 'application/json',
                'Prefer' => 'return=minimal'
            ],
            'json' => [
                'autorizado' => '1',
                'data_autorizado' => $data
            ]
        ]);
        
        // $getToken = $client->request('GET', "https://gmxeqbapboztlivrkfcy.supabase.co/rest/v1/tokens?nomeAluno=eq.$nome_formatado", [
        //     'headers' => [
        //         'apikey' => '',
        //         'Authorization' => 'Bearer ',
        //     ]
        // ]);
       
        // $retornoToken = json_decode($getToken->getBody(), true);
        
        // foreach ($retornoToken as $index => $item) {
        //     if (file_exists("./env/temp_{$item["token"]}.json")) {
        //         $json_file = "./env/temp_{$item["token"]}.json";
        //         $json_data = file_get_contents($json_file);
    
        //         // Decodificar o JSON em uma matriz associativa
        //         $data = json_decode($json_data, true);
    
        //         // Alterar o valor da chave 'status' para 0
        //         $data['status'] = 1;
    
        //         // Codificar de volta para JSON
        //         $json_data = json_encode($data, JSON_PRETTY_PRINT);
    
        //         // Salvar de volta no arquivo
        //         file_put_contents($json_file, $json_data);
        //     }
        // }
        exit;

    } elseif ($_POST['action'] == 'nao_autorizar') {
        // Código para não autorizar aluno e remover (SQL DELETE)
        $id = $_POST['id'];
        $aluno = $_POST['aluno'];

        $nome_formatado = urlencode($aluno);

        // Aqui você faz a conexão com o banco de dados e executa a query de exclusão
        
        $response = $client->request('PATCH', "https://gmxeqbapboztlivrkfcy.supabase.co/rest/v1/autorizacao_saida?nomeAluno=eq.$nome_formatado", [
            'headers' => [
                'apikey' => $apiKey,
                'Authorization' => "Bearer $apiKeySecret",
                'Content-Type' => 'application/json',
                'Prefer' => 'return=minimal'
            ],
            'json' => [
                'autorizado' => '-1',
                'data_autorizado' => $data
            ]
        ]);

        
        // $getToken = $client->request('GET', "https://gmxeqbapboztlivrkfcy.supabase.co/rest/v1/tokens?nomeAluno=eq.$nome_formatado", [
        //     'headers' => [
        //         'apikey' => '',
        //         'Authorization' => 'Bearer ',
        //     ]
        // ]);
       
        // $retornoToken = json_decode($getToken->getBody(), true);
        
        // foreach ($retornoToken as $index => $item) {
        //     if (file_exists("./env/temp_{$item["token"]}.json")) {
        //         $json_file = "./env/temp_{$item["token"]}.json";
        //         $json_data = file_get_contents($json_file);
    
        //         // Decodificar o JSON em uma matriz associativa
        //         $data = json_decode($json_data, true);
    
        //         // Alterar o valor da chave 'status' para 0
        //         $data['status'] = -1;
    
        //         // Codificar de volta para JSON
        //         $json_data = json_encode($data, JSON_PRETTY_PRINT);
    
        //         // Salvar de volta no arquivo
        //         file_put_contents($json_file, $json_data);
        //     }
        // }
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/autorizar_style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="shortcut icon" href="./assets/logo2.jpg" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Cabin:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Fjalla+One&family=Hind:wght@300;400;500;600;700&family=IBM+Plex+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Entrada e saída - SESI Boituva</title>
</head>
<body>
    
    <div id="head">
        <a href="registro.php"><button class="btn"><i class="fa fa-user" href="registro.php"></i></button></a>
        <br>
        
        <img src="assets/logo.jpg" width="150">
        <h1>Lista de saída de alunos</h1>
        <br>
    </div>
</body>

<script>
// evento onclick que será ativo quando chamar o yes e tornará a div visivel ou não
function exibirmore() {
    document.getElementById('more').style.display = 'block';
}
function escondermore() {
    document.getElementById('more').style.display = 'none';
    document.getElementById('responsavel').value = '';
    document.getElementById('parentesco').value = '';
}

let alunos;

const xhr = new XMLHttpRequest();
xhr.open('GET', './api/get_alunos.php', true);
xhr.onload = function() {
    if (xhr.status === 200) {
        const response = JSON.parse(xhr.responseText);
        alunos = response;
        if (response['1']) {
            for (let i in response) {
                // Create a new <div> element
                var divElement = document.createElement("div");

                // Set attributes for the <div> element
                divElement.setAttribute("id", "container");
                divElement.setAttribute("class", `aluno${i}`);

                divElement.innerHTML = `
                <a href="cordenador.php"><ul>${response[i]}</ul></a>
                <hr>
                <br>
                <div class="buttons">
                    <button class="fa fa-check autorizar${i}" style="color:rgb(41, 130, 72)"></button>
                    <button class="fa fa-times nautorizar${i}" style="color:rgb(226, 42, 42)"></button>
                </div>
                <br>
                `;

                // Append the <div> element to the body of the document
                document.body.appendChild(divElement);

                var botaoAutorizar = divElement.querySelector('.autorizar' + i);
                var botaoNautorizar = divElement.querySelector('.nautorizar' + i);

                botaoAutorizar.addEventListener('click', function() {
                    console.log('Autorizar clicado para aluno ' + i);
                    autorizarAluno(i);
                    // Adicione aqui o que deseja fazer quando o botão de autorizar for clicado
                });

                botaoNautorizar.addEventListener('click', function() {
                    console.log('Não autorizar clicado para aluno ' + i);
                    naoAutorizarAluno(i);
                    // Adicione aqui o que deseja fazer quando o botão de não autorizar for clicado
                });
            }
        } else {
            // Se o código não existir, submeter o formulário
            console.log("Não tem nenhum aluno")
        }
    } else {
        console.error('Erro ao verificar código');
    }
};
xhr.send();

function autorizarAluno(id) {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', window.location.href, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if (xhr.status === 200) {
            console.log('Aluno autorizado com sucesso!');
            removerAluno(id);
        } else {
            console.error('Erro ao autorizar aluno');
        }
    };
    xhr.send(`action=autorizar&id=${id}&aluno=${alunos[id]}`);
}

function naoAutorizarAluno(id) {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', window.location.href, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if (xhr.status === 200) {
            console.log('Aluno não autorizado e removido com sucesso!');
            removerAluno(id);
        } else {
            console.error('Erro ao não autorizar aluno');
        }
    };
    xhr.send(`action=nao_autorizar&id=${id}&aluno=${alunos[id]}`);
}


function removerAluno(id) {
    var alunoDiv = document.getElementsByClassName(`aluno${id}`)[0];
    alunoDiv.remove();
}
</script>
</html>