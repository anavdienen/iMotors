<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel=stylesheet href="index.css">
    <link href='https://cdn.boxicons.com/fonts/basic/boxicons.min.css' rel='stylesheet'>
    <title>iMotors | Cadastro de Usuário</title>
</head>
<body class="relacionado">
    
<?php
include('config.php');
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

        <h1>Cadastro</h1>
        
        <div class="login">
        <i class='bx  bx-user'  ></i> 
        <input type="text" name="login" placeholder="Login" required>
        </div>

        <div class="email">
        <i class='bx  bx-envelope'  ></i> 
        <input type="text" name="email" placeholder="E-mail" required>
        </div>

        <div class="senha">
        <i class='bx  bx-lock'  ></i> 
        <input type="password" name="senha" placeholder="Senha" required>
        </div>

        <div class="entrar">
        <p>Já tem uma conta? <a href="login.php">Ir para login.</a></p>
        <input type="submit" value="Cadastrar">
        </div>
    </form>
    </div>
</div></main>
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login = $_POST['login'];
    $email = $_POST['email'];
    $senha = md5($_POST['senha']);
    $nivel = 'USER';

    $query = "INSERT INTO usuario (login, email, senha, nivel) VALUES ('$login', '$email', '$senha', '$nivel')";
    mysqli_query($con, $query);

    echo "<p id='mensagem'>Usuário cadastrado com sucesso!</p>";
}
?>
<br>
<br>
<br>



</body>
</html>