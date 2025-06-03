<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit;
}

include_once("../includes/header.php");
include_once("../config/db.php");
require_once("../config/novadenuncia.php");

$sucesso = "";
$erro = "";
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Nova Denúncia</title>
    <link rel="stylesheet" href="css/feed.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
</head>
<body>
<main class="nova">
    <h1>Nova Denúncia</h1>

    <?php if ($sucesso): ?>
        <p style="color: green;"><?= htmlspecialchars($sucesso) ?></p>
    <?php endif; ?>
    <?php if ($erro): ?>
        <p style="color: red;"><?= htmlspecialchars($erro) ?></p>
    <?php endif; ?>

    <form action="nova_denuncia.php" method="POST" enctype="multipart/form-data" class="form">
        <label for="titulo">Título:</label>
        <input type="text" name="titulo" id="titulo" required>

        <label for="descricao">Descrição:</label>
        <textarea name="descricao" id="descricao" rows="4" required></textarea>

        <label for="localizacao">Localização (Endereço):</label>
        <input type="text" name="localizacao" id="localizacao" required>

        <input type="hidden" name="latitude" id="latitude">
        <input type="hidden" name="longitude" id="longitude">

        <label for="busca">Buscar endereço ou CEP:</label>
        <div class="buscas">
            <input type="text" id="busca" placeholder="Digite um endereço ou CEP">
            <button type="button" onclick="buscarEndereco()">Buscar</button>
        </div>

        <label>Selecione no mapa:</label>
        <div id="map"></div>

        <label for="imagem">Imagem da Cena:</label>
        <input type="file" name="imagem" accept="image/*">

        <input type="submit" value="Registrar Denúncia">
    </form>
</main>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="js/novo_map.js"></script>
<script src="js/header.js"></script>
<script>
function setLatLon(lat, lon) {
    document.getElementById('latitude').value = lat;
    document.getElementById('longitude').value = lon;
}
</script>
</body>
</html>