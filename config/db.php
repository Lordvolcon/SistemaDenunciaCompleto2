<?php 
/**
 * Cria e retorna uma conexão PDO utilizando credenciais definidas por variáveis de ambiente.
 *
 * Variáveis de ambiente esperadas:
 *   DB_HOST     Endereço do servidor MySQL.
 *   DB_USER     Usuário do banco de dados.
 *   DB_PASSWORD Senha do banco de dados.
 */
function conectarBanco() {
    $usuario = getenv('DB_USER');
    $senha = getenv('DB_PASSWORD');
    $servidor = getenv('DB_HOST');
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
