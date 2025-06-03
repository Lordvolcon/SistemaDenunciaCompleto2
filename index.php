<?php 
session_start();
include_once("config/login.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="public/css/reset.css">
    <link rel="stylesheet" href="public/css/login.css">
    <title>Login</title>
</head>
<body>
    <?php if (!empty($erroLogin)): ?>
        <ul>
            <?php foreach ($erroLogin as $erro): ?>
                <li style="color: red;"><?php echo $erro; ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    <form class="login" action="" method="post">
        <img src="" alt="Logo">
        <input class="field" type="text" placeholder="E-mail ou CPF" name="login" id="login">
        <input class="field" type="password" placeholder="Senha" name="senha">
        <input type="submit" value="Entrar" name="submit">
        <label>Ainda não possui conta? <a href="public/cadastro.php">Cadastrar-se</a></label>
    </form>
    <script src="/public/js/formatar.js"></script>
</body>
</html>