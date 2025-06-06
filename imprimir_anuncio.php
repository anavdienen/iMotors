<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Imprimir Anúncio | iMotors</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
            line-height: 1.6;
        }
        .container-impressao {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #5d15bb; 
            text-align: center;
            margin-bottom: 20px;
        }
        .anuncio-detalhes {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        .anuncio-detalhes .info {
            flex: 1; 
            min-width: 250px; 
        }
        .anuncio-detalhes img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
            border: 1px solid #ddd;
            margin-bottom: 15px;
            display: block; 
        }
        p {
            margin-bottom: 10px;
        }
        strong {
            color: #5d15bb; 
        }
        @media print {
            .no-print {
                display: none;
            }
            body {
                margin: 0;
                padding: 0;
                box-shadow: none;
                border: none;
            }
            .container-impressao {
                border: none;
                box-shadow: none;
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <?php
    include('config.php');
    session_start();

    $id_anuncio = isset($_GET['id']) ? intval($_GET['id']) : 0;

    if ($id_anuncio === 0) {
        echo "<p>ID do anúncio não fornecido ou inválido.</p>";
        exit;
    }

    $query = "SELECT * FROM anuncio WHERE id = ? AND autorizado='S'"; 
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $id_anuncio);
    $stmt->execute();
    $result = $stmt->get_result();
    $anuncio = $result->fetch_assoc();
    $stmt->close();

    if (!$anuncio) {
        echo "<p>Anúncio não encontrado ou não autorizado para visualização.</p>";
        exit;
    }
    ?>

    <div class="container-impressao">
        <h1 class="no-print">Detalhes do Anúncio para Impressão</h1>
        
        <div class="anuncio-detalhes">
            <?php if (!empty($anuncio['foto'])): ?>
                <div>
                    <img src="imagens/<?= htmlspecialchars($anuncio['foto']) ?>" alt="Foto do Anúncio">
                </div>
            <?php endif; ?>
            <div class="info">
                <h1><?= htmlspecialchars($anuncio['titulo']) ?></h1>
                <p><strong>Preço:</strong> R$ <?= htmlspecialchars($anuncio['preco']) ?></p>
                <p><strong>Descrição:</strong> <?= htmlspecialchars($anuncio['descricao']) ?></p>
                <p>Anúncio de id: <?= htmlspecialchars($anuncio['id']) ?></p>
            </div>
        </div>

        <div class="no-print">
            <button onclick="window.print()">Imprimir Anúncio</button>
            <button onclick="window.close()">Fechar</button>
        </div>
    </div>
</body>
</html>