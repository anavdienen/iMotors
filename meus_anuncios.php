<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel=stylesheet href="index.css">
    <title>iMotors | Meus Anúncios</title>
</head>
<body> <?php 
include ('config.php');
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit;
}

$id_usuario = $_SESSION['id_usuario'];

$query = "SELECT * FROM anuncio WHERE id_usuario = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
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

<div class="block">
    <h1 id="h1_texto">Seus Anúncios</h1>
</div>

<div class="container-produtos"> <?php while($row = $result->fetch_assoc()): ?>
    <div class="produto">
        <div class="titulo">
        <?= htmlspecialchars($row['titulo']) ?>
        </div>
        <img src="imagens/<?= htmlspecialchars($row['foto']) ?>" alt="Foto do produto">
        <p><?= htmlspecialchars($row['descricao']) ?></p>
        <p>R$ <?= htmlspecialchars($row['preco']) ?></p>
        </div>
    <?php endwhile; ?>
</div>
</body>
</html>