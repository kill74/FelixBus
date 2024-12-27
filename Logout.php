<?php
session_start();
// Destrói a sessão
session_destroy();
// Redireciona para a página de login
header("Location: Login.php");
exit(); // Certifique-se de que o script termina aqui
?>