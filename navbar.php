<?php
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
}
?>
<head>
<link rel="stylesheet" href="style/styleIndex.css">
</head>
<header>
    <h1>FelixBus</h1>
    <br>
    <nav>
        <ul>
            <li><a href="index.php">Página Principal</a></li>
            <?php if ($isLoggedIn): ?>
                <li><a href="perfil.php">Perfil</a></li>
                <li><a href="carteira.php">Carteira</a></li>
            <?php endif; ?>
            <li><a href="horarios.php">Horários</a></li>
            <?php if ($isLoggedIn && ($userRole === 'funcionario' || $userRole === 'admin')): ?>
                <li><a href="AreaGestao.php">Area Gestão</a></li>
            <?php endif; ?>
            <?php if ($isLoggedIn && $userRole === 'admin'): ?>
                <li><a href="AreaAdmin.php">Area Admin</a></li>
            <?php endif; ?>
            <?php if ($isLoggedIn): ?>
                <li><a href="Logout.php">Sair</a></li> <!-- Alterado para Logout.php -->
            <?php else: ?>
                <li><a href="Login.php">Login</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>