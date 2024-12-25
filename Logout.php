<?php
session_start();
//ira destruir a sessão
session_destroy();
//depois da sessao estiver destruida, redireciona para a pagina de login
header("Location: Login.php");
?>