<?php
session_start(); // Inicia a sessão
require_once 'db_connection.php';

// Verifica se o utilizador está logado e se o tipo de utilizador é 3 (administrador)
if (!isset($_SESSION['user_id']) || $_SESSION['tipo_utilizador'] != 3) {
    header("Location: Login.php"); // Redireciona para a página de login
    exit(); // Termina a execução do script
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Administrador</title>
    <link rel="stylesheet" href="styleAreaAdmin.css">
</head>
<body>
    <div class="container">
        <!-- Barra lateral -->
        <div class="sidebar">
            <div class="logo">Painel Admin</div>
            <nav>
                <div class="nav-item"><a href="#">Gestão de Utilizadores</a></div>
                <div class="nav-item"><a href="#">Gestão de Rotas</a></div>
                <div class="nav-item"><a href="#">Gestão de Alertas/Promoções</a></div>
                <div class="nav-item"><a href="index.php">Página Principal</a></div>
                <div class="nav-item"><a href="Login.php">Sair</a></div>
            </nav>
        </div>

        <!-- Conteúdo principal -->
        <div class="main-content">
            <header class="header">
                <h1>Bem-vindo, <?php echo $_SESSION['nome']; ?></h1> <!-- Exibe o nome do administrador -->
            </header>

            <!-- Gestão de Utilizadores -->
            <div class="table-section">
                <h2>Gestão de Utilizadores</h2>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Tipo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Nome do Utilizador</td>
                            <td>email@exemplo.com</td>
                            <td>Cliente</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Gestão de Rotas -->
            <div class="table-section">
                <h2>Gestão de Rotas</h2>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Origem</th>
                            <th>Destino</th>
                            <th>Data</th>
                            <th>Hora</th>
                            <th>Capacidade</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Origem Exemplo</td>
                            <td>Destino Exemplo</td>
                            <td>2023-01-01</td>
                            <td>12:00</td>
                            <td>50</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Gestão de Alertas/Promoções -->
            <div class="form-section">
                <h2>Gestão de Alertas/Promoções</h2>
                <form action="paginaAdmin.php" method="POST">
                    <textarea name="alerta" placeholder="Digite o alerta ou promoção..." rows="4" required></textarea><br>
                    <button type="submit" name="adicionar_alerta">Adicionar Alerta/Promoção</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>