<?php
session_start(); // inicia sessao para validar usuario

if (!isset($_SESSION['user_id'])) { // bloqueia quem nao estiver logado
    header("Location: index.php");
    exit();
}

require_once __DIR__ . '/config.php'; // conexao com o banco

$user_id = $_SESSION['user_id']; // id do usuario logado
$texto = isset($_POST['texto']) ? trim($_POST['texto']) : ''; // texto enviado no form
$imagemPath = null; // caminho da imagem, se tiver

if ($texto === '') { // se texto vazio, nao posta e volta
    header("Location: home.php");
    exit();
}

$uploadDir = __DIR__ . '/uploads/'; // pasta de uploads no servidor
if (!empty($_FILES['imagem']['name']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) { // se veio imagem sem erro
    $nomeArquivo = basename($_FILES['imagem']['name']); // nome base do arquivo
    $destino = $uploadDir . $nomeArquivo; // caminho completo do destino
    if (move_uploaded_file($_FILES['imagem']['tmp_name'], $destino)) { // move o arquivo enviado
        $imagemPath = 'uploads/' . $nomeArquivo; // caminho salvo no banco
    }
}

$texto_esc = $mysqli->real_escape_string($texto); // escapa texto para a query
$imagem_esc = $imagemPath ? "'" . $mysqli->real_escape_string($imagemPath) . "'" : "NULL"; // prepara campo da imagem

$mysqli->query("INSERT INTO POSTAGEM (PERFIL_ID, TEXTO, IMAGEM) VALUES ($user_id, '$texto_esc', $imagem_esc)"); // grava postagem

header("Location: home.php"); // volta para feed
exit();
?>
