<?php
session_start();

// Verifica se o usuário está logado
if ((!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true)) {
    header("location: registro.php");
    exit;
}
require 'C://xampp/vendor/autoload.php';

use GuzzleHttp\Client;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable('C:\\xampp\htdocs\sesi\env');
$dotenv->load();

$apiKey = $_ENV["SUPABASE_APIKEY"];
$apiKeySecret = $_ENV["SUPABASE_APIKEY2"];
$bearerCode = $_ENV["BEARER_CODE"];


$client = new Client();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $data = $_POST['data'];
    $hora =  $_POST['hora'];
    $turma = $_POST['turma'];
    $tabs = $_POST['tabs'];
    $responsavel = $_POST['responsavel'];
    $senha = $_POST['id'];
    $parentesco = $_POST['parentesco'];
    $irSozinho = 0;
    $funcionarioNome = '';

    if (empty($responsavel)) {
        $irSozinho = 1;
        $parentesco = null;
        $responsavel = null;
    }

    $queryaa = $client->request('GET', "https://gmxeqbapboztlivrkfcy.supabase.co/rest/v1/autorizacao_entrada?select=*", [
        'headers' => [
            'apikey' => $apiKey,
            'Authorization' => "Bearer $apiKey"
        ]
    ]);

    $dataaaa = json_decode($queryaa->getBody(), true);

    $total = count($dataaaa);


    $query = $client->request('GET', "https://gmxeqbapboztlivrkfcy.supabase.co/rest/v1/funcionarios?IDfuncionario=eq.$senha&select=*", [
        'headers' => [
            'apikey' => $apiKey,
            'Authorization' => "Bearer $apiKeySecret"
        ]
    ]);

    $funcionarioInfo = json_decode($query->getBody(), true);

    $funcionarioNome = $funcionarioInfo[0]['nomeFuncionario'];


    $response = $client->request('POST', 'https://gmxeqbapboztlivrkfcy.supabase.co/rest/v1/autorizacao_entrada', [
        'headers' => [
            'apikey' => $apiKey,
            'Authorization' => "Bearer $apiKeySecret",
            'Content-Type' => 'application/json',
            'Prefer' => 'return=minimal'
        ],
        'json' => [
            'id' => $total+1,
            'nomeAluno' => $name,
            'turma' => $turma,
            'data' => $data,
            'horario' => $hora,
            'parentesco' => $parentesco,
            'nomeParentesco' => $responsavel,
            'irSozinho' => $irSozinho,
            'funcionario' => $funcionarioNome,
            'senha' => $senha,
            'data_autorizado' => null,
            'autorizado' => '0',
            'tipo' => 'entrada'
        ]
    ]);

    // append the parameter to the URL
    $token = uniqid();
 
    
    $headers = [
        'Authorization' => 'Bearer ' . $bearerCode
    ];

    // URL da solicitação POST
    $url = 'http://localhost/sesi/api/verificarstatus.php';

    // Parâmetros na URL
    $params = array(
        'name' => $name,
        'tipo' => 'entrada',
        'token' => $token
    );

    // Cabeçalho de autorização Bearer
    $headers = array(
        'Authorization: Bearer ' . $bearerCode
    );

    // Inicializa a sessão cURL
    $ch = curl_init($url);

    // Define as opções da solicitação cURL
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Retorna o resultado da solicitação como uma string
    curl_setopt($ch, CURLOPT_POST, true); // Define o método de solicitação como POST
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params)); // Define os parâmetros da solicitação
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); // Define o cabeçalho de autorização

    // Executa a solicitação cURL e obtém a resposta
    $response = curl_exec($ch);

    // Verifica se houve algum erro durante a solicitação
    if ($response === false) {
        echo 'Erro ao fazer a solicitação: ' . curl_error($ch);
    } else {
        $url = "http://localhost/sesi/aguardando.php"; // replace with the actual URL of the page you want to redirect to

        $urlWithParam = $url . "?" . http_build_query(array("token" => $token));

    
        header("Location: $urlWithParam"); 
    }

    // Fecha a sessão cURL
    curl_close($ch);

       
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="./assets/logo2.jpg" type="image/x-icon">
    <title>Entrada e saída - SESI Boituva</title>
    <link rel="stylesheet" href="./css/home_css.css">
</head>
<body>
    
    <div id="head">
    <br>
    <a href="registro.php"><button class="btn"><i class="fa fa-user" href="./registro.php"></i></button></a>
    
    <img src="assets/logo.jpg" width="150">
    <h1>Autorização de entrada de estudante</h1>
    <br>
    </div>
    
    <form id='formfoda' action="entrada.php" method="post">
        <div id="container">
        <br>
        <div id="bordanome">
            <label for="name">Nome do aluno:</label>
            <br>
            <input list="alunos" id="name" name="name" required>
            <datalist id="alunos">
                <!-- Opções de aluno serão adicionadas dinamicamente aqui -->
            </datalist>
        </div>
        <br>
        <br>
        <label for="data">Data e horário:</label>
        <br>
        <input type="date" id="data" name="data">
        <input type="time" id="hora" name="hora" required>
        <br>
        <br>
        <label for="turma">Turma: </label>
        <select id="turma" name="turma" required>
            <option value="1a">1ºA</option>
            <option value="1b">1ºB</option>
            <option value="2a">2ºA</option>
            <option value="2b">2ºB</option>
            <option value="3a">3ºA</option>
            <option value="3b">3ºB</option>
            <option value="4a">4ºA</option>
            <option value="4b">4ºB</option>
            <option value="5a">5ºA</option>
            <option value="5b">5ºB</option>
            <option value="6a">6ºA</option>
            <option value="6b">6ºB</option>
            <option value="7a">7ºA</option>
            <option value="7b">7ºB</option>
            <option value="8a">8ºA</option>
            <option value="8b">8ºB</option>
            <option value="9a">9ºA</option>
            <option value="9b">9ºB</option>
            <option value="1ae">1ºA - EM</option>
            <option value="1be">1ºB - EM</option>
            <option value="2ae">2ºA - EM</option>
            <option value="2be">2ºB - EM</option>
            <option value="3ae">3ºA - EM</option>
            <option value="3be">3ºB - EM</option>
        </select>
        <br>
        <br>
        <p> Familiar irá buscá-lo?</p>
                <div class="container">
                    <div class="tabs">
                    <input type="radio" id="yes" name="tabs" onclick="exibirmore()">
                    <label class="tab" for="yes">Sim</label>
                    <input type="radio" id="no" name="tabs"  onclick="escondermore()" checked="">
                    <label class="tab" for="no">Não</label>
                    <span class="glider"></span>
            </div>
        </div>
       
        <div id="more" style="display:none">
        <label for="responsavel">Nome do responsável:</label>
        <input type="text" id="responsavel" name="responsavel">
        <br>
        <br>
        <label for="parentesco">Parentesco:</label>
        <input type="text" id="parentesco" name="parentesco">
        </div>
        
        <br>
        <label for="id">ID do funcionário: </label>
        <br>
        <input type='password' id='senha' name='id' required>
        <p id='error-cod'></p>
        <br>
        <br>
        <div id="center">
        <button class="botao" type="submit" value="Enviar">Registrar</button>
        <br>
        <br>
        
    </div>
    </div>
    </div>
    </form>
    </body>
    </html>

    <script>
    var alunos = [];

    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'api/get_nomealunos.php', true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            console.log(response)
            for(i in response){
                alunos.push(response[i].nome);
            }

            // Popula o datalist com os nomes dos alunos
            var datalist = document.getElementById("alunos");
            alunos.forEach(function(aluno) {
                console.log(alunos)
                var option = document.createElement("option");
                option.value = aluno;
                datalist.appendChild(option);
            });
        } else {
            console.error('Erro ao verificar código');
            console.log('asdasd')
        }
    };
    xhr.send();

    
    
    document.getElementById("formfoda").addEventListener("submit", function(event) {
        const id = document.getElementById('senha').value.trim();
        console.log(id)

        event.preventDefault();

        xhr.open('GET', `api/checarfuncionario.php?id=${id}`, true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                console.log(`api/checarfuncionario.php?id=${id}`)
                console.log(response)
                if(response.res){
                    document.getElementById('error-cod').style.color = 'white';
                    document.getElementById('error-cod').textContent = '';

                    document.getElementById('formfoda').submit();
                } else {
                    document.getElementById('error-cod').style.color = 'red';
                    document.getElementById('error-cod').textContent = 'Código invalido';
                }
            } else {
                console.error('Erro ao verificar código');
            }
        };
        xhr.send();
    })

    var input = document.getElementById('senha');

    // Add an event listener for input events
    input.addEventListener('input', function(event) {
      // Get the input value
      var inputValue = event.target.value;

      // Remove non-numeric characters using regular expression
      var numericValue = inputValue.replace(/\D/g, '');

      // Update the input value with only numeric characters
      event.target.value = numericValue;
    })
    // evento onclick que será ativo quando chamar o yes e tornará a div visivel ou não
    function exibirmore() {
        document.getElementById('more').style.display = 'block';
    }
    function escondermore() {
        document.getElementById('more').style.display = 'none';
        document.getElementById('responsavel').value = '';
        document.getElementById('parentesco').value = '';
    }
</script>

</script>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cabin:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Fjalla+One&family=Hind:wght@300;400;500;600;700&family=IBM+Plex+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
