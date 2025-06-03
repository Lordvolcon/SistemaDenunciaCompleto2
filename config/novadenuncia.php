<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = trim($_POST['titulo']);
    $descricao = trim($_POST['descricao']);
    $localizacao = trim($_POST['localizacao']);
    $latitude = trim($_POST['latitude']);
    $longitude = trim($_POST['longitude']);
    $imagem = null;

    if (!empty($_FILES['imagem']['name'])) {
        $pastaDestino = "../uploads/";
        if (!is_dir($pastaDestino)) {
            mkdir($pastaDestino, 0755, true);
        }
        $nomeArquivo = uniqid() . "_" . basename($_FILES['imagem']['name']);
        $caminhoArquivo = $pastaDestino . $nomeArquivo;

        if (move_uploaded_file($_FILES['imagem']['tmp_name'], $caminhoArquivo)) {
            $imagem = $caminhoArquivo;
        } else {
            $erro = "Falha ao fazer upload da imagem.";
        }
    }

    if (!empty($descricao) && !empty($localizacao) && !empty($latitude) && !empty($longitude)) {
        $conexao = conectarBanco();

        $sql = "INSERT INTO denuncia (titulo, descricao, localizacao, imagem, id_usuario, latitude, longitude) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conexao->prepare($sql);
        $resultado = $stmt->execute([
            $titulo,
            $descricao,
            $localizacao,
            $imagem,
            $_SESSION['id_usuario'],
            $latitude,
            $longitude
        ]);

        if ($resultado) {
            $sucesso = "Denúncia registrada com sucesso!";
        } else {
            $erro = "Erro ao registrar denúncia.";
        }
    } else {
        $erro = "Todos os campos devem ser preenchidos.";
    }
}
?>