<header>
    <h1>FelixBus</h1>
    <br>
    <nav>
        <ul>
            <li><a href="index.php">Página Principal</a></li>
            <?php if (isset($isLoggedIn) && $isLoggedIn): ?>
                <li><a href="perfil.php">Perfil</a></li>
                <li><a href="carteira.php">Carteira</a></li>
            <?php endif; ?>
            <li><a href="horarios.php">Horários</a></li>
            <?php if (isset($isLoggedIn, $userRole) && $isLoggedIn && ($userRole === 'funcionario' || $userRole === 'admin')): ?>
                <li><a href="paginaFuncionario.php">Página Funcionário</a></li>
            <?php endif; ?>
            <?php if (isset($isLoggedIn, $userRole) && $isLoggedIn && $userRole === 'admin'): ?>
                <li><a href="paginaAdmin.php">Página Admin</a></li>
            <?php endif; ?>
            <?php if (isset($isLoggedIn) && $isLoggedIn): ?>
                <li><a href="logout.php">Sair</a></li>
            <?php else: ?>
                <li><a href="Login.php">Login</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>
