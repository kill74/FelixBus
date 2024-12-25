<?php
session_start();
require_once 'db_connection.php';  

$isLoggedIn = isset($_SESSION['user_id']);
$userRole = 'visitor';

if ($isLoggedIn) {
    $userId = $_SESSION['user_id'];
    $tipoUtilizador = $_SESSION['tipo_utilizador'];
    
    switch($tipoUtilizador) {
        case 1:
            $userRole = 'cliente';
            break;
        case 2:
            $userRole = 'funcionario';
            break;
        case 3:
            $userRole = 'admin';
            break;
    }

    // Define cookies para o papel do utilizador sem tempo de expiração (cookie de sessão)
    setcookie("user_role", $userRole, 0, "/"); // 0 = cookie de sessão
} else {
    // Redireciona para a página de login se não estiver logado
    header("Location: Login.php");
    exit();
}
?>