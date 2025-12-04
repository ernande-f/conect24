<?php
session_start(); // inicia sessao para gravar dados de login

require_once __DIR__ . '/config.php'; // carrega configuracao e conexao

if ($_SERVER["REQUEST_METHOD"] == "POST") { // so processa se o form foi enviado
    $email = $_POST['email']; // email digitado
    $password = $_POST['password']; // senha digitada
    
    $email_esc = $mysqli->real_escape_string($email); // escapa email para a query

    $consulta = $mysqli->query("SELECT ID, SENHA_HASH, NOME, EMAIL FROM PERFIL WHERE EMAIL='" . $email_esc . "' LIMIT 1"); // busca usuario pelo email
    if ($consulta && ($row = $consulta->fetch_assoc()) && hash('sha256', $password) === $row['SENHA_HASH']) { // valida se achou usuario e se o hash da senha confere
        $_SESSION['user_id'] = $row['ID']; // guarda ID na sessao
        $_SESSION['nome_usuario'] = $row['NOME']; // guarda nome na sessao
        $_SESSION['user_email'] = $row['EMAIL']; // guarda email na sessao
        header("Location: perfil.php"); // redireciona para perfil logado
        exit();
    } else {
        header("Location: index.php?erro=1"); // volta para login com indicador de erro
        exit();
    }
}
?>
