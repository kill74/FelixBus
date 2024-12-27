<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Gestão - Funcionário/Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f7f7f7;
            display: flex;
            flex-direction: column;
            height: 100vh;
        }
        .header {
            background-color: #3498db;
            color: white;
            padding: 15px;
            text-align: center;
            font-size: 1.5em;
        }
        .main-content {
            padding: 20px;
            background-color: #ecf0f1;
            overflow-y: auto;
            flex-grow: 1;
        }
        h1 {
            margin: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: white;
            border-radius: 5px;
            overflow: hidden;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 12px;
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
        .form-section {
            margin-top: 20px;
            padding: 15px;
            background-color: #fff;
            border-radius: 5px;
        }
        .form-section h2 {
            margin-top: 0;
        }
        .action-link {
            color: #3498db;
            text-decoration: none;
        }
        .action-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <?php require 'navbar.php'  ?>
    <!-- Cabeçalho -->
    <header class="header">
        Área de Gestão - Funcionário/Admin
    </header>

    <!-- Conteúdo principal -->
    <div class="main-content">
        <!-- Gestão de Saldo -->
        <div class="table-section">
            <h2>Gestão de Saldo</h2>
            <table>
                <thead>
                    <tr>
                        <th>Cliente</th>
                        <th>Saldo Atual</th>
                        <th>Editar Saldo</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Cliente Exemplo</td>
                        <td>€50,00</td>
                        <td>
                            <form action="#" method="POST" style="display:inline;">
                                <input type="number" name="novo_saldo" placeholder="Novo Saldo" step="0.01" required>
                                <button type="submit">Atualizar</button>
                            </form>
                        </td>
                    </tr>
                    <!-- Adicione mais linhas conforme necessário -->
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
                        <th>Origem</th>
                        <th>Destino</th>
                        <th>Data</th>
                        <th>Hora</th>
                        <th>Estado</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Cliente Exemplo</td>
                        <td>Origem Exemplo</td>
                        <td>Destino Exemplo</td>
                        <td>2024-01-01</td>
                        <td>12:00</td>
                        <td>Confirmado</td>
                        <td><a href="#" class="action-link">Editar</a></td>
                    </tr>
                    <!-- Adicione mais linhas conforme necessário -->
                </tbody>
            </table>
        </div>

        <!-- Adicionar Novo Bilhete -->
        <div class="form-section">
            <h2>Adicionar Novo Bilhete</h2>
            <form action="#" method="POST">
                <input type="text" name="cliente" placeholder="Nome do Cliente" required>
                <input type="text" name="origem" placeholder="Origem" required>
                <input type="text" name="destino" placeholder="Destino" required>
                <input type="date" name="data_viagem" required>
                <input type="time" name="hora_partida" required>
                <button type="submit">Adicionar Bilhete</button>
            </form>
        </div>
    </div>
    
</body>
</html>
