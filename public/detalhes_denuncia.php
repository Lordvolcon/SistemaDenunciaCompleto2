<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit;
}

include_once("../includes/header.php");
include_once("../config/db.php");

$conexao = conectarBanco();

if (!isset($_GET['id'])) {
    echo "<p style='color: red; text-align: center;'>ID da denúncia não fornecido.</p>";
    exit;
}

$id = intval($_GET['id']);
$sql = "SELECT * FROM denuncia WHERE id_denuncia = ?";
$stmt = $conexao->prepare($sql);
$stmt->execute([$id]);
$denuncia = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$denuncia) {
    echo "<p style='color: red; text-align: center;'>Denúncia não encontrada.</p>";
    exit;
}

// Comentários
$sqlComentarios = "SELECT c.*, u.nome, 
    EXISTS (SELECT 1 FROM administrador a WHERE a.id_usuario = u.id_usuario) AS is_admin 
    FROM comentario c 
    INNER JOIN usuario u ON c.id_usuario = u.id_usuario 
    WHERE c.id_denuncia = ? ORDER BY c.data_comentario DESC";
$stmtComentarios = $conexao->prepare($sqlComentarios);
$stmtComentarios->execute([$id]);
$comentarios = $stmtComentarios->fetchAll(PDO::FETCH_ASSOC);

// Novo comentário
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['comentario'])) {
    $novoComentario = trim($_POST['comentario']);
    if ($novoComentario) {
        $stmtAdd = $conexao->prepare("INSERT INTO comentario (id_denuncia, id_usuario, texto) VALUES (?, ?, ?)");
        $stmtAdd->execute([$id, $_SESSION['usuario'], $novoComentario]);
        header("Location: detalhes_denuncia.php?id=$id");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Detalhes da Denúncia</title>
    <link rel="stylesheet" href="css/feed.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
    </style>
</head>
<body>

<div class="detalhes">
    <h2><?= htmlspecialchars($denuncia['titulo']) ?></h2>
    <p><strong>Endereço:</strong> <?= htmlspecialchars($denuncia['localizacao']) ?></p>
    <p><strong>Data:</strong> <?= date("d/m/Y H:i", strtotime($denuncia['data_cadastro'])) ?></p>
    <p><strong>Status:</strong> <?= htmlspecialchars($denuncia['status']) ?></p>
    <p><strong>Descrição:</strong><br><?= nl2br(htmlspecialchars($denuncia['descricao'])) ?></p>

    <?php if (!empty($denuncia['imagem'])): ?>
        <div class="imagem-container">
            <img src="<?= htmlspecialchars($denuncia['imagem']) ?>" alt="Imagem da denúncia">
        </div>
    <?php endif; ?>

    <div id="map"></div>

    <div class="comentarios">
        <h3>Comentários</h3>

        <form method="POST" class="form-comentario">
            <textarea name="comentario" placeholder="Escreva um comentário..." required></textarea>
            <button type="submit">Enviar</button>
        </form>

        <?php foreach ($comentarios as $coment): ?>
            <div class="comentario">
                <strong><?= htmlspecialchars($coment['nome']) ?>
                    <?= $coment['is_admin'] ? '<span class="tag-admin">[admin]</span>' : '' ?>
                </strong>
                <span class="data"><?= date("d/m/Y H:i", strtotime($coment['data_comentario'])) ?></span>
                <p><?= nl2br(htmlspecialchars($coment['texto'])) ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="js/header.js"></script>
<script>
    const lat = <?= json_encode($denuncia['latitude']) ?>;
    const lon = <?= json_encode($denuncia['longitude']) ?>;

    if (lat && lon) {
        var map = L.map('map').setView([lat, lon], 15);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
        }).addTo(map);
        L.marker([lat, lon]).addTo(map);
    } else {
        document.getElementById('map').innerHTML = "<p style='color:white;text-align:center;'>Coordenadas não disponíveis.</p>";
    }
</script>

</body>
</html>