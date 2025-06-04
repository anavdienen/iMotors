<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel=stylesheet href="index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>iMotors | Editar Anúncio</title>
</head>
<body>

<?php
include('config.php');
session_start();

if (!isset($_SESSION['id_usuario']) || $_SESSION['nivel'] != 'ADM') {
    echo "<p class='message error'>Acesso negado. Você deve ser um administrador.</p>";
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<p class='message error'>ID do anúncio inválido ou não fornecido.</p>";
    exit;
}

$id_anuncio = intval($_GET['id']);
$anuncio = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = $_POST['titulo'] ?? '';
    $descricao = $_POST['descricao'] ?? '';
    $preco = $_POST['preco'] ?? 0;
    
    $foto_atual = $_POST['foto_atual'] ?? '';
    $nova_foto = $foto_atual;

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $arquivo = $_FILES['foto'];
        $diretorio_destino = "imagens/";
        $nome_original = basename($arquivo['name']);
        $tipo_arquivo = strtolower(pathinfo($nome_original, PATHINFO_EXTENSION));

        $tipos_permitidos = array('jpg', 'jpeg', 'png', 'gif');
        $tamanho_maximo = 5 * 1024 * 1024;

        if (in_array($tipo_arquivo, $tipos_permitidos) && $arquivo['size'] <= $tamanho_maximo) {
            $nova_foto = uniqid() . '_' . $nome_original;
            $caminho_final = $diretorio_destino . $nova_foto;

            if (move_uploaded_file($arquivo['tmp_name'], $caminho_final)) {
                if (!empty($foto_atual) && file_exists($diretorio_destino . $foto_atual)) {
                    unlink($diretorio_destino . $foto_atual);
                }
                echo "<p class='message success'>Nova foto enviada com sucesso!</p>"; // Mensagem para a foto
            } else {
                echo "<p class='message error'>Erro ao mover a nova foto.</p>";
                $nova_foto = $foto_atual; 
            }
        } else {
            echo "<p class='message error'>Tipo de arquivo inválido ou tamanho excessivo para a nova foto.</p>";
            $nova_foto = $foto_atual;
        }
    }

    $query_update = "UPDATE anuncio SET titulo = ?, descricao = ?, preco = ?, foto = ? WHERE id = ?";
    $stmt_update = $con->prepare($query_update);
    $stmt_update->bind_param("ssdssi", $titulo, $descricao, $preco, $nova_foto, $id_anuncio); 

    if ($stmt_update->execute()) {
        echo "<p class='message success'>Anúncio atualizado com sucesso!</p>";
        // Re-buscar os dados para que o formulário mostre os dados atualizados
        $stmt_select = $con->prepare("SELECT * FROM anuncio WHERE id = ?");
        $stmt_select->bind_param("i", $id_anuncio);
        $stmt_select->execute();
        $result_select = $stmt_select->get_result();
        $anuncio = $result_select->fetch_assoc();
        $stmt_select->close();
    } else {
        echo "<p class='message error'>Erro ao atualizar anúncio: " . htmlspecialchars($stmt_update->error) . "</p>";
    }
    $stmt_update->close();
}

if (!$anuncio) { 
    $stmt_select = $con->prepare("SELECT * FROM anuncio WHERE id = ?");
    $stmt_select->bind_param("i", $id_anuncio);
    $stmt_select->execute();
    $result_select = $stmt_select->get_result();
    $anuncio = $result_select->fetch_assoc();
    $stmt_select->close();
}


if (!$anuncio) {
    echo "<p class='message error'>Anúncio não encontrado ou você não tem permissão para editá-lo.</p>";
    exit;
}
?>

<header>
    <nav class="navigation">
        <a href="index.php" class="logo"><span>i</span>M<span>otor</span>S </a>
        <ul class="nav-menu">
            <li class="nav-item"><a class="nav-link" href="index.php">Menu</a></li>
            <?php if (isset($_SESSION['id_usuario'])): ?>
            <li class="nav-item"><a class="nav-link" href="cadastrar_anuncio.php">Cadastrar Anúncio</a></li>
            <li class="nav-item"><a class="nav-link" href="meus_anuncios.php">Meus Anúncios</a></li>
            <?php if (isset($_SESSION['nivel']) && $_SESSION['nivel'] == 'ADM'): ?>
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
    <div id="cadastro">
        <div class="caixa">
            <h1>Editar Anúncio</h1>

            <form method="post" enctype="multipart/form-data">
                
                <div class="input-com-icone"> 
                    <i class="fas fa-heading"></i>
                    <input type="text" name="titulo" placeholder="Título do anúncio" value="<?= htmlspecialchars($anuncio['titulo']) ?>" required>
                </div>

                <div class="input-com-icone"> 
                    <i class="fas fa-align-left"></i>
                    <textarea name="descricao" placeholder="Descrição" required><?= htmlspecialchars($anuncio['descricao']) ?></textarea>
                </div>

                <div class="input-com-icone"> 
                    <i class="fas fa-dollar-sign"></i>
                    <input type="number" name="preco" placeholder="Preço" value="<?= htmlspecialchars($anuncio['preco']) ?>" step="0.01" required>
                </div>

                <div class="campo-imagem-upload">
                    <label>Imagem atual do veículo:</label>
                    <?php if (!empty($anuncio['foto'])): ?>
                        <img src="imagens/<?= htmlspecialchars($anuncio['foto']) ?>" alt="Imagem Atual" id="current-preview-foto" style="max-width: 150px; margin-bottom: 10px; border-radius: 5px;">
                        <input type="hidden" name="foto_atual" value="<?= htmlspecialchars($anuncio['foto']) ?>">
                    <?php else: ?>
                        <p style="color: var(--white);">Nenhuma imagem atual.</p>
                    <?php endif; ?>

                    <label for="input-foto">Alterar imagem (opcional):</label>
                    <input type="file" id="input-foto" name="foto" accept="image/*">
                    <img id="preview-foto" src="#" alt="Pré-visualização da Nova Imagem" style="display: none; max-width: 100px; max-height: 100px; margin-top: 10px; border-radius: 5px;">
                </div>

                <div class="entrar">
                    <input type="submit" value="Salvar Alterações">
                    <p><a href="aprovar_anuncio.php">Cancelar e Voltar</a></p>
                </div>
            </form>
        </div>
    </div>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const inputFoto = document.getElementById('input-foto');
        const previewFoto = document.getElementById('preview-foto');

        if (inputFoto && previewFoto) {
            inputFoto.addEventListener('change', function(event) {
                const arquivo = event.target.files[0];

                if (arquivo) {
                    if (arquivo.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            previewFoto.src = e.target.result;
                            previewFoto.style.display = 'block';
                            const currentPreview = document.getElementById('current-preview-foto');
                            if (currentPreview) {
                                currentPreview.style.display = 'none';
                            }
                        };
                        reader.readAsDataURL(arquivo);
                    } else {
                        previewFoto.src = "#";
                        previewFoto.style.display = 'none';
                        alert("Por favor, selecione um arquivo de imagem (JPG, PNG, GIF, etc.).");
                        inputFoto.value = '';
                        const currentPreview = document.getElementById('current-preview-foto');
                        if (currentPreview) {
                            currentPreview.style.display = 'block';
                        }
                    }
                } else {
                    previewFoto.src = "#";
                    previewFoto.style.display = 'none';
                    const currentPreview = document.getElementById('current-preview-foto');
                    if (currentPreview) {
                        currentPreview.style.display = 'block';
                    }
                }
            });
        }
    });
</script>

</body>
</html>