<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Administrador</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            display: flex;
        }
        .container {
            display: flex;
            flex-direction: row;
            width: 100%;
            height: 100vh;
        }
        .sidebar {
            width: 250px;
            background-color: #2c3e50;
            color: white;
            padding: 20px;
        }
        .sidebar .logo {
            font-size: 1.5em;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }
        .sidebar .nav-item {
            margin: 10px 0;
        }
        .sidebar .nav-item a {
            color: white;
            text-decoration: none;
            padding: 10px;
            display: block;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .sidebar .nav-item a:hover {
            background-color: #34495e;
        }
        .main-content {
            flex-grow: 1;
            padding: 20px;
            background-color: #ecf0f1;
        }
        .header {
            background-color: #3498db;
            color: white;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: white;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        table th {
            background-color: #3498db;
            color: white;
        }
        .form-section input, .form-section button {
            margin: 5px;
            padding: 10px;
            font-size: 1rem;
        }
    </style>
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
                <h1>Bem-vindo, Nome do Administrador</h1>
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
