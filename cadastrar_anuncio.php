<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel=stylesheet href="index.css">
    <title>iMotors | Cadastro de anúncio</title>
</head>
<body class="relacionado">
    
<?php
include('config.php');
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
    <form method="post" enctype="multipart/form-data">

        <h1>Cadastrar anúncio</h1>
        
        <div class="login">
        <input type="text" name="titulo" placeholder="Título do anúncio" required>
        </div>

        <div class="email">
        <input type="text" name="descricao" placeholder="Descrição" required>
        </div>

        <div class="senha">
        <input type="number" name="preco" placeholder="Preço" required>
        </div>

        <div class="campo-imagem-upload"> 
        <label for="input-foto">Imagem do veículo:</label> <input type="file" name="foto" id="input-foto" accept="image/*" required>
                    
        <img id="preview-foto" src="#" alt="Pré-visualização da Imagem" style="display: none; max-width: 100px; max-height: 100px; margin-top: 10px; border-radius: 5px;">
        </div>

        <div class="entrar">
        <p>Ir para <a href="meus_anuncios.php">meus anúncios.</a></p>
        <input type="submit" value="Cadastrar">
        </div>
    </form>
    </div>
</div></main>
<?php
if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];
    $id_usuario = $_SESSION['id_usuario'];

    // Foto
    $foto = uniqid() . '_' . $_FILES['foto']['name'];
    $tmp_name = $_FILES['foto']['tmp_name'];
    $pasta = "imagens/";
    
    if (move_uploaded_file($tmp_name, $pasta . $foto)) {
        $stmt = $con->prepare("INSERT INTO anuncio (titulo, descricao, preco, foto, id_usuario) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdsi", $titulo, $descricao, $preco, $foto, $id_usuario);
        $stmt->execute();

        echo "<p id='mensagem'>Anúncio cadastrado com sucesso! Aguarde autorização do Admin para publicá-lo.</p>";
    } else {
        echo "<p id='mensagem'>Erro ao mover a foto!</p>";
    }
}
?>

</body>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const inputFoto = document.getElementById('input-foto');
        const previewFoto = document.getElementById('preview-foto');


        if (inputFoto) {
            inputFoto.addEventListener('change', function(event) {
                const arquivo = event.target.files[0];

                if (arquivo) {
                    if (arquivo.type.startsWith('image/')) {
                        const reader = new FileReader(); 

                        reader.onload = function(e) {
                            
                            previewFoto.src = e.target.result;
                            previewFoto.style.display = 'block';
                        };

                        reader.readAsDataURL(arquivo); 
                    } else {
                       
                        previewFoto.src = "#";
                        previewFoto.style.display = 'none';
                        alert("Por favor, selecione um arquivo de imagem (JPG, PNG, GIF, etc.).");
                        inputFoto.value = ''; 
                    }
                } else {
                    
                    previewFoto.src = "#";
                    previewFoto.style.display = 'none';
                }
            });
        }
    });
</script>
</html>

