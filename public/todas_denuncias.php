<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit;
}

include_once("../includes/header.php");
include_once("../config/db.php");

$conexao = conectarBanco();

$sql = "SELECT id_denuncia, titulo, localizacao, status, data_cadastro, latitude, longitude FROM denuncia ORDER BY data_cadastro DESC";

$stmt = $conexao->prepare($sql);
$stmt->execute();

$denuncias = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Todas as Denúncias</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/feed.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
</head>
<body>
<main>
    <h1>Todas as Denúncias</h1>

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

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
<?php foreach ($denuncias as $denuncia):
    $lat = $denuncia['latitude'] ?: '-15.77972';
    $lon = $denuncia['longitude'] ?: '-47.92972';
?>
    var map<?= $denuncia['id_denuncia'] ?> = L.map('map-<?= $denuncia['id_denuncia'] ?>').setView([<?= $lat ?>, <?= $lon ?>], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap'
    }).addTo(map<?= $denuncia['id_denuncia'] ?>);

    var marker<?= $denuncia['id_denuncia'] ?> = L.marker([<?= $lat ?>, <?= $lon ?>]).addTo(map<?= $denuncia['id_denuncia'] ?>);

    map<?= $denuncia['id_denuncia'] ?>.fitBounds([marker<?= $denuncia['id_denuncia'] ?>.getLatLng()]);
<?php endforeach; ?>
</script>
<script src="js/header.js"></script>
</body>
</html>