<?php 
$erroLogin = [];

if ($_SERVER['REQUEST_METHOD'] == "POST") { 

    $login = trim($_POST['login']);
    $senha = trim($_POST['senha']);

    if (isset($_POST['submit']) && !empty($login) && !empty($senha)) {
        include_once('config/db.php');
        $conexao = conectarBanco();

        $sql = "SELECT * FROM usuario WHERE email = ? OR cpf = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->execute([$login, $login]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            if (password_verify($senha, $result['senha'])) {
                $_SESSION['usuario'] = $result['id_usuario'];
                $_SESSION['id_usuario'] = $result['id_usuario'];
                $_SESSION['nome'] = $result['nome'];

                // VERIFICA SE É ADMIN
                $sqlAdmin = "SELECT id_admin FROM administrador WHERE id_admin = ?";
                $stmtAdmin = $conexao->prepare($sqlAdmin);
                $stmtAdmin->execute([$result['id_usuario']]);
                $admin = $stmtAdmin->fetch(PDO::FETCH_ASSOC);

                if ($admin) {
                    $_SESSION['admin'] = true;
                    header('Location: public/admin_denuncias.php');
                    exit;
                } else {
                    $_SESSION['admin'] = false;
                    header('Location: public/todas_denuncias.php');
                    exit;
                }

            } else {
                $erroLogin[] = 'Email/CPF ou senha inválidos.';
            }
        } else {
            $erroLogin[] = 'Email/CPF ou senha inválidos.';
        }

    } else {
        if (empty($login)) {
            $erroLogin[] = "O campo login deve ser preenchido.";
        }

        if (empty($senha)) {
            $erroLogin[] = "O campo senha deve ser preenchido.";
        }
    }
}
?>