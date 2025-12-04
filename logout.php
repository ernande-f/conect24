<?php
session_start(); // inicia sessao para poder limpá-la
session_unset(); // limpa variaveis da sessao
session_destroy(); // encerra a sessao

header("Location: index.php"); // redireciona para tela de login
exit();
?>
