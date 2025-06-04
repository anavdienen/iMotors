<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" context="IE=edge">
    <title>iMotors | Login</title>
    <link href='https://cdn.boxicons.com/fonts/basic/boxicons.min.css' rel='stylesheet'>
    <link rel=stylesheet href="index.css">
</head>
<body class="relacionado">
    
<?php 
include ('config.php');
session_start();
?>
    <header>
        <nav class="navigation">
        <a href="index.php" class="logo"><span>i</span>M<span>otor</span>S </a>

        <ul class="nav-menu">
        <li class="nav-item"><a class="nav-link" href="index.php">Menu</a></li>
        </ul>

        <div class="menu">
        <span class= bar></span>
        <span class= bar></span>
        <span class= bar></span>
        </div>

        </nav>
    </header>

        <main><div id="cadastro">
    <div class="caixa">
    <form method="post">

        <h1>Login</h1>
        
        <div class="login">
        <i class='bx  bx-user'  ></i> 
        <input type="text" name="login" placeholder="Login" required>
        </div>

        <div class="senha">
        <i class='bx  bx-lock'  ></i> 
        <input type="password" name="senha" placeholder="Senha" required>
        </div>

        <div class="entrar">
        <p>Ainda não tem uma conta? <a href="cadastrar_usuario.php">Crie uma.</a></p>
        <input type="submit" name="botao" value="Entrar">
        </div>
    </form>
    </div>
</div></main>
<?php

if (@$_REQUEST['botao']=="Entrar")
{
    $login = $_POST['login'];
    $senha = md5($_POST['senha']);

    $query = "SELECT * FROM usuario WHERE login='$login' AND senha='$senha'";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        $_SESSION['id_usuario'] = $row['id'];
        $_SESSION['nome_usuario'] = $row['login'];
        $_SESSION['nivel'] = $row['nivel'];

        header('Location: index.php');
        exit;
    } else {
        echo "Login ou senha inválidos.";
    }
}
?>

</body>
</html>