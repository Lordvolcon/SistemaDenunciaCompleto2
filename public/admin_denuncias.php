<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit;
}

include_once("../includes/header.php");
include_once("../config/db.php");

$conexao = conectarBanco();

// Verifica se o usuario logado é administrador
$stmtAdmin = $conexao->prepare("SELECT COUNT(*) FROM administrador WHERE id_usuario = ?");
$stmtAdmin->execute([$_SESSION['usuario']]);
$isAdmin = $stmtAdmin->fetchColumn() > 0;

if (!$isAdmin) {
    header("Location: Feed.php");
    exit;
}

// Atualiza status da denuncia
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id_denuncia'], $_POST['novo_status'])) {
        $stmt = $conexao->prepare("UPDATE denuncia SET status = ? WHERE id_denuncia = ?");
        $stmt->execute([$_POST['novo_status'], $_POST['id_denuncia']]);
    }

    if (isset($_POST['comentario_admin']) && trim($_POST['comentario_admin']) !== '') {
        $stmt = $conexao->prepare("INSERT INTO comentario (id_denuncia, id_usuario, texto) VALUES (?, ?, ?)");
        $stmt->execute([$_POST['id_denuncia'], $_SESSION['usuario'], trim($_POST['comentario_admin'])]);
    }
}

$sql = "SELECT d.*, u.nome FROM denuncia d JOIN usuario u ON d.id_usuario = u.id_usuario ORDER BY d.data_cadastro DESC";
$stmt = $conexao->prepare($sql);
$stmt->execute();
$denuncias = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Painel Administrativo</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/admin_denuncias.css">
    <link rel="stylesheet" href="css/header.css">
</head>
<body>
<main>
    <h1 style="text-align:center">Painel do Administrador</h1>

    <?php foreach ($denuncias as $d): ?>
        <div class="card-admin">
            <h3><?= htmlspecialchars($d['titulo']) ?></h3>
            <p><strong>Usuário:</strong> <?= htmlspecialchars($d['nome']) ?></p>
            <p><strong>Data:</strong> <?= date("d/m/Y H:i", strtotime($d['data_cadastro'])) ?></p>
            <p><strong>Status:</strong> <?= htmlspecialchars($d['status']) ?></p>
            <p><strong>Localização:</strong> <?= htmlspecialchars($d['localizacao']) ?></p>
            <p><strong>Descrição:</strong> <?= nl2br(htmlspecialchars($d['descricao'])) ?></p>
            <?php if (!empty($d['imagem'])): ?>
                <img src="<?= htmlspecialchars($d['imagem']) ?>" alt="Imagem da denuncia">
            <?php endif; ?>

            <form method="POST">
                <input type="hidden" name="id_denuncia" value="<?= $d['id_denuncia'] ?>">
                <label for="status">Alterar status:</label>
                <select name="novo_status">
                    <option value="Aberta" <?= $d['status'] == 'Aberta' ? 'selected' : '' ?>>Aberta</option>
                    <option value="Em análise" <?= $d['status'] == 'Em análise' ? 'selected' : '' ?>>Em análise</option>
                    <option value="Resolvida" <?= $d['status'] == 'Resolvida' ? 'selected' : '' ?>>Resolvida</option>
                    <option value="Encerrada" <?= $d['status'] == 'Encerrada' ? 'selected' : '' ?>>Encerrada</option>
                </select>
                <button type="submit">Atualizar Status</button>
            </form>

            <form method="POST" class="form-comentario">
                <input type="hidden" name="id_denuncia" value="<?= $d['id_denuncia'] ?>">
                <label for="comentario_admin">Adicionar Comentário:</label>
                <textarea name="comentario_admin" rows="3" placeholder="Comentário como admin..."></textarea>
                <button type="submit">Enviar Comentário</button>
            </form>

    <?php
    $stmtC = $conexao->prepare("
        SELECT c.texto, u.nome, c.data_comentario,
            (SELECT COUNT(*) FROM administrador a WHERE a.id_usuario = u.id_usuario) as is_admin
        FROM comentario c
        JOIN usuario u ON c.id_usuario = u.id_usuario
        WHERE c.id_denuncia = ?
        ORDER BY c.data_comentario DESC
    ");
    $stmtC->execute([$d['id_denuncia']]);
    $comentarios = $stmtC->fetchAll(PDO::FETCH_ASSOC);
    ?>
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
    <?php endforeach; ?>
</main>
<script src="js/header.js"></script>
</body>
</html>