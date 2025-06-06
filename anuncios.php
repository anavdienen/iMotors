<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>iMotors | Anúncios</title>
    <link rel=stylesheet href="index.css">
    <link href='https://cdn.boxicons.com/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body> <?php
    include('config.php');
    session_start();

    $conditions = ["autorizado='S'"];
    $params = [];
    $types = "";

    if (isset($_GET['busca_titulo']) && $_GET['busca_titulo'] !== '') {
        $busca_titulo = '%' . $_GET['busca_titulo'] . '%';
        $conditions[] = "titulo LIKE ?";
        $params[] = $busca_titulo;
        $types .= "s";
    }

    if (isset($_GET['min_preco']) && $_GET['min_preco'] !== '') {
        $min_preco = floatval($_GET['min_preco']);
        $conditions[] = "preco >= ?";
        $params[] = $min_preco;
        $types .= "d";
    }

    if (isset($_GET['max_preco']) && $_GET['max_preco'] !== '') {
        $max_preco = floatval($_GET['max_preco']);
        $conditions[] = "preco <= ?";
        $params[] = $max_preco;
        $types .= "d";
    }

    $sql = "SELECT * FROM anuncio";
    if (!empty($conditions)) {
        $sql .= " WHERE " . implode(" AND ", $conditions);
    }

    $stmt = $con->prepare($sql);

    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();

    $no_results_message = "";
    if ($result->num_rows === 0 && (isset($_GET['busca_titulo']) || isset($_GET['min_preco']) || isset($_GET['max_preco']))) {
        $no_results_message = "<p class='message error' style='margin-top: 30px;'>Nenhum anúncio encontrado com os critérios de busca.</p>";
    } elseif ($result->num_rows === 0) {
        $no_results_message = "<p class='message info' style='margin-top: 30px;'>Não há anúncios disponíveis no momento.</p>";
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
            <h1 id="h1_texto">Anúncios Disponíveis</h1>
        </div>

        <div class="main-content-wrapper"> <div class="search-form-container">
                <form method="GET" action="anuncios.php">
                    <div class="input-com-icone search-input">
                        <i class="fas fa-search"></i>
                        <input type="text" name="busca_titulo" placeholder="Buscar por título..." value="<?= htmlspecialchars($_GET['busca_titulo'] ?? '') ?>">
                    </div>
                    
                    <div class="input-com-icone search-input">
                        <i class="fas fa-money-bill-wave"></i>
                        <input type="number" name="min_preco" placeholder="Preço Mín." step="0.01" value="<?= htmlspecialchars($_GET['min_preco'] ?? '') ?>">
                    </div>
                    
                    <div class="input-com-icone search-input">
                        <i class="fas fa-money-bill-wave"></i>
                        <input type="number" name="max_preco" placeholder="Preço Máx." step="0.01" value="<?= htmlspecialchars($_GET['max_preco'] ?? '') ?>">
                    </div>

                    <button type="submit" class="search-btn">Buscar Anúncios</button>
                    <?php if (isset($_GET['busca_titulo']) || isset($_GET['min_preco']) || isset($_GET['max_preco'])): ?>
                        <a href="anuncios.php" class="clear-search-btn">Limpar Busca</a>
                    <?php endif; ?>
                </form>
            </div>
            <div class="container-produtos">
                <?php echo $no_results_message; // Exibe a mensagem de nenhum resultado aqui ?>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<div class='produto'>";
                        echo "<div class='titulo'>";
                        echo htmlspecialchars($row['titulo']);
                        echo "</div>";
                        if ($row['foto']) {
                            echo "<img src='imagens/" . htmlspecialchars($row['foto']) . "' alt='Foto do veículo'>";
                        } else {
                            echo "<img src='imagens/placeholder.png' alt='Sem foto disponível'>";
                        }
                        echo "<p>" . htmlspecialchars($row['descricao']) . "</p>";
                        echo "<p class='preco'>R$ " . htmlspecialchars($row['preco']) . "</p>";
                        echo "<div class='acoes'><a href='imprimir_anuncio.php?id=" . $row['id'] . "' target='_blank' class='btn-imprimir'>Imprimir</a></div>";
                        echo "</div>";
                    }
                }
                $stmt->close();
                ?>
            </div>
        </div> </main> </body>
</html>