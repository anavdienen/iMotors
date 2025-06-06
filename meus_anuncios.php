<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel=stylesheet href="index.css">
    <link href='https://cdn.boxicons.com/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body> <?php
    include('config.php');
    session_start();

    if (!isset($_SESSION['id_usuario'])) {
        header('Location: login.php');
        exit;
    }

    $id_usuario = $_SESSION['id_usuario'];

    $sql = "SELECT * FROM anuncio WHERE id_usuario = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $id_usuario); 
    $stmt->execute();
    $result = $stmt->get_result();

    $no_results_message = ""; 
    if ($result->num_rows === 0) {
        $no_results_message = "<p class='message info' style='margin-top: 30px;'>Você ainda não possui anúncios cadastrados.</p>";
    }
    ?>
    <header>
        <nav class="navigation">
            <a href="index.php" class="logo"><span>i</span>M<span>otor</span>S </a>

            <ul class="nav-menu">
                <?php if (isset($_SESSION['id_usuario'])): ?>
                <li class="nav-item"><a class="nav-link" href="index.php">Menu</a></li>
                <li class="nav-item"><a class="nav-link" href="cadastrar_anuncio.php">Cadastrar Anúncio</a></li>
                <li class="nav-item"><a class="nav-link" href="meus_anuncios.php">Meus Anúncios</a></li>
                <li class="nav-item"><a class="nav-link" href="anuncios.php">Ver Anúncios</a></li>
                <?php if (isset($_SESSION['nivel']) && $_SESSION['nivel'] == 'ADM'): ?>
                <li class="nav-item"><a class="nav-link" href="aprovar_anuncio.php">Aprovar Anúncios</a></li>
                <?php endif; ?>
                <li class="nav-item"><a class="nav-link" href="logout.php">Sair</a></li>
                <?php else: ?>
                <li class="nav-item"><a class="nav-link" href="index.php">Menu</a></li>
                <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                <li class="nav-item"><a class="nav-link" href="cadastrar_usuario.php">Cadastrar Usuário</a></li>
                <li class="nav-item"><a class="nav-link" href="anuncios.php">Ver Anúncios</a></li>
                <?php endif; ?>
            </ul>
            <div class="menu">
                <span class= bar></span>
                <span class= bar></span>
                <span class= bar></span>
            </div>
        </nav>
    </header>

    <main> <div class="block">
            <h1 id="h1_texto">Seus Anúncios</h1>
            <p><a href='index.php'>Voltar ao menu</a></p>
        </div>

        <div class="main-content-wrapper" style="justify-content: center;"> <div class="container-produtos">
                <?php echo $no_results_message; ?>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<div class='produto'>";
                        echo "<div class='titulo'>";
                        echo htmlspecialchars($row['titulo']);
                        echo "</div>";
                        if ($row['foto']) {
                            echo "<img src='imagens/" . htmlspecialchars($row['foto']) . "' alt='Foto do produto'>";
                        } else {
                            echo "<img src='imagens/placeholder.png' alt='Sem foto disponível'>";
                        }
                        echo "<p>" . htmlspecialchars($row['descricao']) . "</p>";
                        echo "<p class='preco'>R$ " . htmlspecialchars($row['preco']) . "</p>";
                        
                        echo "<div class='acoes'>";
                        echo "<a href='editar_anuncio.php?id=" . $row['id'] . "' class='btn-imprimir'>Editar</a>"; 
                        echo "<a href='deletar_anuncio.php?id=" . $row['id'] . "' onclick=\"return confirm('Tem certeza que deseja excluir este anúncio? A ação é irreversível!')\" class='btn-imprimir'>Excluir</a>"; 
                        echo "<a href='imprimir_anuncio.php?id=" . $row['id'] . "' target='_blank' class='btn-imprimir'>Imprimir</a>";
                        echo "</div>"; 
                        
                        echo "</div>"; 
                    }
                }
                $stmt->close();
                ?>
            </div>
        </div> </main> </body>
</html>