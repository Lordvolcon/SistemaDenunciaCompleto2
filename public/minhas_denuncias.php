<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit;
}

include_once("../includes/header.php");
include_once("../config/db.php");

$conexao = conectarBanco();

$id_usuario = $_SESSION['id_usuario'];

$sql = "SELECT id_denuncia, titulo, descricao, localizacao, status, data_cadastro 
        FROM denuncia 
        WHERE id_usuario = ? 
        ORDER BY data_cadastro DESC";

$stmt = $conexao->prepare($sql);
$stmt->execute([$id_usuario]);

$denuncias = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Minhas Denúncias</title>
    <link rel="stylesheet" href="css/feed.css">
    <link rel="stylesheet" href="css/header.css">
    <script src="js/header.js" defer></script>
</head>
<body>
<main>
    <h1>Minhas Denúncias</h1>

    <?php if (!empty($denuncias)): ?>
        <?php foreach ($denuncias as $denuncia): ?>
            <div class="card">
                <div class="textocard">
                    <p><strong>Título: </strong><?= htmlspecialchars($denuncia['titulo']) ?> </p>
                    <p><strong>Data: </strong><?= htmlspecialchars($denuncia['data_cadastro']) ?> </p>
                    <p><strong>Localização: </strong><?= htmlspecialchars($denuncia['localizacao']) ?> </p>
                    <p class="t"><strong>Status: </strong><?= htmlspecialchars($denuncia['status']) ?> </p>
                    <a href="detalhes_denuncia.php?id=<?= $denuncia['id_denuncia'] ?>" class="btn-detalhes">Ver detalhes</a>
                </div>
                <div class="map-container" id="map-<?= $denuncia['id_denuncia'] ?>"></div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Não há denúncias registradas.</p>
    <?php endif; ?>
</main>
<script src="js/map.js"></script>
</body>
</html>