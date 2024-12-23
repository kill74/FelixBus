<?php
session_start(); // Certifique-se de iniciar a sessão
// Verifica se o usuário está logado
$isLoggedIn = isset($_SESSION['user_id']);
$userRole = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;
?>