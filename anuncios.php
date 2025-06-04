<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>iMotors | Anúncios</title>
    <link rel=stylesheet href="index.css">
</head>
<body> <?php
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

        <?php if (isset($_SESSION['nivel']) && $_SESSION['nivel'] == 'ADM'): ?>
        <li class="nav-item"><a class="nav-link" href="aprovar_anuncio.php">Aprovar Anúncios</a></li>
        <?php endif; ?>
        <li class="nav-item"><a class="nav-link" href="logout.php">Sair</a></li>
        <?php else: ?>
           <li class="nav-item"><a class="nav-link" href="index.php">Menu</a></li>
           <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
           <li class="nav-item"><a class="nav-link" href="cadastrar_usuario.php">Cadastrar Usuário</a></li>
        
        <?php endif; ?>    
        <li class="bx bx-search"></li> 
        </ul>

        <div class="menu">
        <span class= bar></span>
        <span class= bar></span>
        <span class= bar></span>
        </div>

        </nav>
    </header>

<?php

$query = "SELECT * FROM anuncio WHERE autorizado='S'";
$result = mysqli_query($con, $query);
?>

<div class="block"> <h1 id="h1_texto">Anúncios Disponíveis</h1>
</div>

<div class="container-produtos"> <?php
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<div class='produto'>"; echo "<div class='titulo'>";
        echo htmlspecialchars($row['titulo']);
        echo "</div>";
        if ($row['foto']) {
            echo "<img src='imagens/" . htmlspecialchars($row['foto']) . "' alt='Foto do veículo'>"; // Removi width='200' para o CSS controlar
        } else {
             // Opcional: exibir uma imagem placeholder se não houver foto
            echo "<img src='imagens/placeholder.png' alt='Sem foto disponível'>";
        }
        echo "<p>" . htmlspecialchars($row['descricao']) . "</p>";
        echo "<p class='preco'>R$ " . htmlspecialchars($row['preco']) . "</p>"; // Adicionei classe 'preco' se for estilizar separadamente
        
        // Opcional: Adicionar um link "Ver Detalhes" para cada anúncio
        // echo "<div class='acoes'><a href='detalhes_anuncio.php?id=" . $row['id'] . "'>Ver Detalhes</a></div>";
        
        echo "</div>"; // Fecha a div.produto
    }
    ?>
</div>
</body>
</html>