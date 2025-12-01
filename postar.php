<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

require_once __DIR__ . '/config.php';

$user_id = $_SESSION['user_id'];
$texto = isset($_POST['texto']) ? trim($_POST['texto']) : '';
$imagemPath = null;

if ($texto === '') {
    header("Location: home.php");
    exit();
}

$uploadDir = __DIR__ . '/uploads/';
if (!empty($_FILES['imagem']['name']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
    $nomeArquivo = basename($_FILES['imagem']['name']);
    $destino = $uploadDir . $nomeArquivo;
    if (move_uploaded_file($_FILES['imagem']['tmp_name'], $destino)) {
        $imagemPath = 'uploads/' . $nomeArquivo;
    }
}

$texto_esc = $mysqli->real_escape_string($texto);
$imagem_esc = $imagemPath ? "'" . $mysqli->real_escape_string($imagemPath) . "'" : "NULL";

$mysqli->query("INSERT INTO POSTAGEM (PERFIL_ID, TEXTO, IMAGEM) VALUES ($user_id, '$texto_esc', $imagem_esc)");

header("Location: home.php");
exit();
?>
