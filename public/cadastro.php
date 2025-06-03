<?php 
require_once("../config/db.php");
include_once("../config/validacao.php");
$sucesso = false;
if ($_SERVER['REQUEST_METHOD'] == "POST") { 
    if (empty($erros)) {
        $sucesso = true;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/login.css">
    <title>Cadastro</title>
</head>
<body>

    <?php if (!empty($erros)): ?>
        <ul>
            <?php foreach ($erros as $erro): ?>
                <li style="color: red;"><?php echo $erro; ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form class="login" action="" method="post">
        <p><?php echo ""?></p>
        <img src="" alt="Logo">
        <input type="text" class="field" placeholder="Nome" name="nome">
        <input class="field" type="text" placeholder="CPF" name="cpf" maxlength="14" required>
        <input class="field" type="email" placeholder="E-mail" name="email">
        <input class="field" type="password" placeholder="Senha" name="senha">
        <input class="field" type="password" placeholder="Confirme a senha" name="confirmar_senha">
        <input type="submit" value="Cadastrar">
        <label>Já possui conta? <a href="../index.php">Faça Login</a></label>
    </form>

    <?php if ($sucesso): ?>
        <p id="sucesso" style="color: green;">Cadastrado com sucesso! Redirecionando...</p>

        <script>
            setTimeout(function() {
                window.location.href = '../index.php';
            }, 3000);
        </script>
        <script>
            document.getElementById('cpf').addEventListener('input', function (e) {
              let value = e.target.value.replace(/\D/g, '');
              value = value.replace(/(\d{3})(\d)/, '$1.$2');
              value = value.replace(/(\d{3})(\d)/, '$1.$2');
              value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
              e.target.value = value;
            });
        </script>

    <?php endif; ?>
</body>
</html>