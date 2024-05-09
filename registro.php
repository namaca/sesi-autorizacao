<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrar</title>
    <link rel="stylesheet" href="./css/registro_style.css">
    <link rel="shortcut icon" href="./assets/logo2.jpg" type="image/x-icon">
</head>
<body>
    
    <div id="head">
    <br>
    <img src="assets/logo.jpg" width="150">
    <h1>Login de acesso à supervisão de entrada e saída</h1>
    <br>
    </div>
    <div id="container">
    <br>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div id="bordanome">
            <label for="login">Login:</label>
            <br>
            <input type="text" id="login" name="login" required>
            <br>
            <br>
            <label for="pass">Senha:</label>
            <br>
            <input type="password" id="pass" name="pass" required>
            <div id="center">
                <input type="submit" id="loginpass" class="botao" value="Entrar">
            </div>
        </div>
    </form>
    <br>
    <br>
    </div>

    </body>
</html>

<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login = $_POST['login'];
    $senha = $_POST['pass'];

    // Verificando se o login e a senha são "admin" e "admin"
    if ($login == "admin" && $senha == "admin") {
        // Redireciona para autorizar.php se o login e a senha estiverem corretos
        $_SESSION["loggedin"] = true;
        $_SESSION["user_type"] = 'admin';
        header("Location: autorizar.php");
        exit();
    } elseif($login == 'func' && $senha == 'func') {
        $_SESSION["loggedin"] = true;
        $_SESSION["user_type"] = 'funcionario';
        header("Location: home.php");
    } else {
        echo '<script>alert("Login ou senha incorretos.");</script>';
    }
}
?>
