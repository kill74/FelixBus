<?php
//Verifica se o usuário está logado
$isLogged = isset($_SESSION['user_id']);
$userRole = $_SESSION['user_role'] : null;
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

<!--so quem tiver login como funcionario ou admin ira conseguir ver este dois-->
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