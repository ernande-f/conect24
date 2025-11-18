<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conect24 - Cadastro</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body class="login-page">
    <div class="container-login">
        
        <h2>Cadastre-se</h2>
        <form action="cadastro.php" method="post">
            <div class="form-group">
                <label for="nome">Nome completo:</label>
                <input type="text" id="nome" name="nome" required>
            </div>
            
            <div class="form-group">
                <label for="email_novo">Email:</label>
                <input type="email" id="email_novo" name="email_novo" required>
            </div>
            
            <div class="form-group">
                <label for="senha_nova">Senha:</label>
                <input type="password" id="senha_nova" name="senha_nova" required>
            </div>
        
            <div class="form-group">
                <label for="nascimento">Data de nascimento:</label>
                <input type="date" id="nascimento" name="nascimento" required>
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn">Criar conta</button>
            </div>
        </form>
        
        <div class="login-links">
            <p>Já tem uma conta?</p>
            <a href="index.php">Fazer login</a>
        </div>
    </div>
</body>
</html>

<?php
session_start();

require_once __DIR__ . '/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $mysqli->real_escape_string($_POST['nome']);
    $email = $mysqli->real_escape_string($_POST['email_novo']);
    $senha = $mysqli->real_escape_string($_POST['senha_nova']);
    $data_nascimento = $mysqli->real_escape_string($_POST['nascimento']);

    $senha_hash = hash('sha256', $senha);

    $query = "INSERT INTO perfil (nome, senha_hash, email, data_nasc) VALUES ('$nome', '$senha_hash', '$email', '$data_nascimento')";
    if ($mysqli->query($query) === TRUE) {
        $_SESSION['mensagem'] = "Cadastro realizado com sucesso!";
        header("Location: index.php");
        exit();
    } else {
        $_SESSION['mensagem'] = "Erro ao cadastrar: " . $mysqli->error;
    }
}
?>