<?php
    $sql = "SELECT descricao, imagem, latitude, longitude, status, data_criacao FROM denuncia ORDER BY data_criacao DESC";
    $stmt = $conexao->prepare($sql);
    $stmt->execute();
    $denuncias = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>