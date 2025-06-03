<?php 
function conectarBanco() {        
    $usuario = 'root';
    $senha = '030212079945472989915071';
    $servidor = 'localhost';
    $banco = 'proj';

    try {
        $pdo = new PDO("mysql:host=$servidor;dbname=$banco", $usuario, $senha);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        echo $e->getMessage();
        return null;
    }
}

$conexao = conectarBanco();
?>