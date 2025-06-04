<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" context="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel=stylesheet href="index.css">
    <title>iMotors | Página Inicial</title>
</head>
<body>
    
<?php
include('config.php');
session_start();

?>
    <header>
        <nav class="navigation">
        <a href="index.php" class="logo"><span>i</span>M<span>otor</span>S </a>

        <ul class="nav-menu">
        <?php if (isset($_SESSION['id_usuario'])): ?>

        <li class="nav-item"><a class="nav-link" href="index.php">Menu</a></li>
        <li class="nav-item"><a class="nav-link" href="cadastrar_anuncio.php">Cadastrar Anúncio</a></li>
        <li class="nav-item"><a class="nav-link" href="meus_anuncios.php">Meus Anúncios</a></li>

        <?php if ($_SESSION['nivel'] == 'ADM'): ?>
        <li class="nav-item"><a class="nav-link" href="aprovar_anuncio.php">Aprovar Anúncios</a></li>
        <?php endif; ?>
        <li class="nav-item"><a class="nav-link" href="logout.php">Sair</a></li>
        <?php else: ?>
        
           <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
           <li class="nav-item"><a class="nav-link" href="cadastrar_usuario.php">Cadastrar Usuário</a></li>
        
        <?php endif; ?>    
        </ul>

        <div class="menu">
        <span class= bar></span>
        <span class= bar></span>
        <span class= bar></span>
        </div>

        </nav>
    </header>

    <main>
        <section class="home">
        <div class="home-text">
            <h4 class="text-h4">iMotors: Sua Conexão Automotiva</h4>
            <h1 class="text-h1">O Mercado de Veículos na Sua Mão</h1>
        <p>Encontre o carro ou a moto perfeita para você ou anuncie seu veículo de forma rápida e segura. Nossa plataforma conecta paixões automotivas no Brasil inteiro.</p>
        <a href="login.php" class="home-btn">Conecte-se</a>
        <a href="anuncios.php" class="home-btn">Anúncios</a>
        </div>
        <div class="home-img">
            <img src="imagens/main.png" alt="veículos">
        </div>
        </section>
    </main>
</div>



</body>
</html>