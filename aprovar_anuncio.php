<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel=stylesheet href="index.css">
    <title>iMotors | Aprovar Anúncios</title>
</head>
<body> <?php
include('config.php');
session_start();

if (!isset($_SESSION['id_usuario']) || $_SESSION['nivel'] != 'ADM') {
    echo "Acesso negado.";
    exit;
}

if (isset($_GET['aprovar'])) {
    $id = intval($_GET['aprovar']);
    $stmt = $con->prepare("UPDATE anuncio SET autorizado='S' WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header('Location: aprovar_anuncio.php');
    exit;
}


if (isset($_GET['reprovar'])) {
    $id = intval($_GET['reprovar']);
    
    $stmt_foto = $con->prepare("SELECT foto FROM anuncio WHERE id=?");
    $stmt_foto->bind_param("i", $id);
    $stmt_foto->execute();
    $result_foto = $stmt_foto->get_result();
    $row_foto = $result_foto->fetch_assoc();
    $stmt_foto->close();

    if ($row_foto && !empty($row_foto['foto'])) {
        $foto = $row_foto['foto'];
        $caminho = "imagens/" . $foto;
        if (file_exists($caminho)) {
            unlink($caminho); 
        }
    }

    $stmt_delete = $con->prepare("DELETE FROM anuncio WHERE id=?");
    $stmt_delete->bind_param("i", $id);
    $stmt_delete->execute();
    $stmt_delete->close();
    
    header('Location: aprovar_anuncio.php');
    exit;
}

$query = "SELECT * FROM anuncio WHERE autorizado != 'S' OR autorizado IS NULL";
$result = mysqli_query($con, $query);

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

<div class="block">
    <h1 id="h1_texto">Anúncios Pendentes</h1>
</div>

<div class="container-produtos"> <?php
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<div class='produto'>"; echo "<div class='titulo'>";
        echo htmlspecialchars($row['titulo']);
        echo "</div>";
        echo "<p>" . htmlspecialchars($row['descricao']) . "</p>";
        if ($row['foto']) {
            echo "<img src='imagens/" . htmlspecialchars($row['foto']) . "' alt='Foto do veículo'>"; 
        } else {
            echo "<img src='imagens/placeholder.jpg' alt='Sem foto disponível'>";
        }
        echo "<p class='preco'>R$ " . htmlspecialchars($row['preco']) . "</p>";
        
        echo "<div class='acoes'>"; echo "<a href='aprovar_anuncio.php?aprovar=" . $row['id'] . "'>Aprovar</a>";
        echo "<br><a href='aprovar_anuncio.php?reprovar=" . $row['id'] . "' onclick=\"return confirm('Tem certeza que deseja excluir este anúncio?')\">Reprovar</a>";
        echo "<br><a href='editar_anuncio.php?id=" . $row['id'] . "'>Editar</a>";
        echo "</div>"; 
        echo "</div>";
    }
    ?>
</div>

</body>
</html>