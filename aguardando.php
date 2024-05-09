<?php
if(($_SERVER["REQUEST_METHOD"] == "GET")){
    if(!isset($_GET["token"])){
        echo '{"msg": "Token invalido", "error": true}';
        exit;
    }
} else {
    echo '{"msg": "Request method invalido", "error": true}';
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Console Log nas Informações Recebidas</title>
    <link rel="stylesheet" href="./css/aguardando.css">
    <link rel="shortcut icon" href="./assets/logo2.jpg" type="image/x-icon">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>
<body>
    <div class="nav">
        <div class="pagAnterior">
            <a id="retornarIcon" href="home.php"><span class="material-symbols-outlined"> last_page </span></a>
        </div>
        <div class="navLogo">
            <img id="logoSesi" src="./assets/logo.jpg" width="150">
        </div>
        
    </div>
    
    <br> 
    <div class="main">
       <div class="container">
            <h2 id="response">Aguardando autorização...</h2>
            <img id="checkImg" src="./assets/checkimg.png" alt="">
        </div> 
    </div>
    
    <script>
        const searchParams = new URLSearchParams(window.location.search);

        function atualizarTexto() {
            var responseElement = document.getElementById("response");
            var texto = responseElement.innerHTML;
            var pontos = (texto.match(/\./g) || []).length;

            if (pontos < 3) {
                responseElement.innerHTML += ".";
            } else {
                responseElement.innerHTML = texto.replace(".", "");
            }
        }

        intervalo = setInterval(atualizarTexto, 1000);

        // Função para imprimir as informações recebidas no console
        function logNotification() {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        console.log(xhr.responseText)
                        var notification = JSON.parse(xhr.responseText);
                        console.log("Informações recebidas:", notification);
                        if(notification.payload.includes('sucesso')){
                          document.getElementById('response').innerText = 'Autorizado';
                          document.getElementById('checkImg').style.display = "flex";

                        } else {
                          document.getElementById("checkImg").src="./assets/checkimgblock.png";
                          document.getElementById('checkImg').style.display = "flex";
                          document.getElementById('response').innerText = 'Não autorizado';
                        }
                        clearInterval(intervalo);
                        setTimeout(() => {
                          window.location = '/sesi/home'
                        }, 4000);
                    } else {
                        console.error("Erro ao carregar informações:", xhr.status, xhr.statusText);
                    }
                }
            };
            xhr.open("GET", `./api/getAutorizacao.php?token=${searchParams.get('token')}`, true);
            xhr.send();
        }

        // Chama a função logNotification() quando a página é carregada
        window.onload = logNotification;
    </script>
</body>
</html>
