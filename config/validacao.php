<?php 
$erros = [];
$sucesso = false;
include_once('db.php');
$conexao = conectarBanco();

function limparDados($dado) {
    return htmlspecialchars(stripslashes(trim($dado)));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = limparDados($_POST['nome']);
    $email = limparDados($_POST['email']);
    $senha = limparDados($_POST['senha']);
    $confirmar_senha = limparDados($_POST['confirmar_senha']);
    $cpf = limparDados($_POST["cpf"]);


    
    $sql = "SELECT COUNT(*) FROM usuario WHERE email = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->execute([$email]);
    $existeUsuario = $stmt->fetchColumn();
    $stmt = $conexao->prepare("SELECT COUNT(*) FROM usuario WHERE cpf = ?");
    $stmt->execute([$cpf]);
    $existeCpf = $stmt->fetchColumn();



    function validarCadastro($email, $nome, $senha, $confirmar_senha, $cpf) {
        $erros = [];
    
        if ($GLOBALS['existeUsuario'] > 0) {
            $erros[] = 'Email já cadastrado!';
        }

        if ($GLOBALS['existeCpf'] > 0) {
            $erros[] = 'CPF já cadastrado!';
        }
    
        if (!$senha || strlen($senha) < 6) {
            $erros[] = "A senha é obrigatória e deve ter pelo menos 6 caracteres!";
        }
    
        if ($senha !== $confirmar_senha) {
            $erros[] = "As senhas não coincidem!";
        }

        if (!$nome || strlen($nome) < 3) {
            $erros[] = "O nome é obrigatório e deve ter pelo menos 3 caracteres!";
        }
        
        if (!preg_match("/^\d{3}\.\d{3}\.\d{3}-\d{2}$/", $cpf)) {
            $erros[] = "CPF inválido! Use o formato 000.000.000-00";
        }

    
        return $erros;
    }
    
    $erros = validarCadastro($email, $nome, $senha, $confirmar_senha, $cpf);
    
    if (empty($erros)) {
        $senhahash = password_hash($senha, PASSWORD_DEFAULT);

        $sql = "INSERT INTO usuario (nome, email, senha, data_cadastro, cpf) VALUES (?, ?, ?, NOW(), ?)";
        $stmt = $conexao->prepare($sql);
        $insert = $stmt->execute([$nome, $email, $senhahash, $cpf]);
        
        if ($insert) {
            $sucesso = true;
        } else {
            $erros[] = "Erro ao cadastrar usuário.";
        }
    }
}
?>
