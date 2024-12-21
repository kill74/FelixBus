<?php
require 'logged.php';
?>

<header>
    <h1>FelixBus</h1>
    <br>
    <nav>
        <ul>
            <li><a href="index.php">Página Principal</a></li>
            <li><a href="perfil.php">Perfil</a></li>
            <li><a href="carteira.php">Carteira</a></li>
            <li><a href="horarios.php">Horários</a></li>

            <!-- Apenas usuários com login como funcionário ou admin verão isso -->
            <?php if ($isLoggedIn && $userRole === 'funcionario'): ?>
                <li><a href="paginaFuncionario.php">Página Funcionário</a></li>
            <?php endif; ?>

            <?php if ($isLoggedIn && $userRole === 'admin'): ?>
                <li><a href="paginaFuncionario.php">Página Funcionário</a></li>
                <li><a href="paginaAdmin.php">Página Admin</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>
