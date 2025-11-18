<?php
session_start();

require_once __DIR__ . '/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    $email_esc = $mysqli->real_escape_string($email);

    $consulta = $mysqli->query("SELECT ID, SENHA_HASH, NOME, EMAIL FROM PERFIL WHERE EMAIL='" . $email_esc . "' LIMIT 1");
    if ($consulta && ($row = $consulta->fetch_assoc()) && hash('sha256', $password) === $row['SENHA_HASH']) {
        $_SESSION['user_id'] = $row['ID'];
        $_SESSION['nome_usuario'] = $row['NOME'];
        $_SESSION['user_email'] = $row['EMAIL'];
        header("Location: perfil.php");
        exit();
    } else {
        header("Location: index.php?erro=1");
        exit();
    }
}
?>