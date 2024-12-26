<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Funcionário</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
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
    </style>
</head>
<body>
    <div class="container">
        <!-- Barra lateral -->
        <div class="sidebar">
            <div class="logo">Portal Funcionário</div>
            <nav>
                <div class="nav-item"><a href="#">Dashboard</a></div>
                <div class="nav-item"><a href="#">Clientes</a></div>
                <div class="nav-item"><a href="#">Bilhetes</a></div>
                <div class="nav-item"><a href="editPerfil.php">Editar Perfil</a></div>
                <div class="nav-item"><a href="index.php">Página Principal</a></div>
            </nav>
        </div>

        <!-- Conteúdo principal -->
        <div class="main-content">
            <header class="header">
                <h1>Bem-vindo, Nome do Funcionário</h1>
            </header>

            <!-- Gestão de Saldos -->
            <div class="table-section">
                <h2>Gestão de Saldos</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Saldo Atual</th>
                            <th>Editar Saldo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Exemplo de um cliente -->
                        <tr>
                            <td>Nome do Cliente</td>
                            <td>€100,00</td>
                            <td>
                                <form action="#" method="POST" style="display:inline;">
                                    <input type="hidden" name="cliente_id" value="1">
                                    <input type="number" name="saldo" step="0.01" placeholder="Novo Saldo" required>
                                    <button type="submit" name="update_saldo">Atualizar</button>
                                </form>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Gestão de Bilhetes -->
            <div class="table-section">
                <h2>Gestão de Bilhetes</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Cliente</th>
                            <th>Data da Viagem</th>
                            <th>Hora da Partida</th>
                            <th>Estado</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Exemplo de um bilhete -->
                        <tr>
                            <td>Nome do Cliente</td>
                            <td>2024-12-31</td>
                            <td>14:00</td>
                            <td>Confirmado</td>
                            <td><a href="#" class="action-link">Editar</a></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
