<?php
// Inicia a sessão para autenticação
session_start();
require_once 'db_connection.php'; // Inclui o ficheiro de conexão à base de dados

// Verifica se o utilizador está autenticado como administrador
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    echo "<script>alert('Acesso negado. Faça login como administrador.');</script>";
    echo "<script>window.location.href='Login.php';</script>";
    exit;
}

// Obtém o ID e nome do administrador autenticado (debugging)
$admin_id = $_SESSION['user_id'];
$sql_admin = "SELECT nome FROM utilizadores WHERE id = ?";
$stmt = $conn->prepare($sql_admin);
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$admin_result = $stmt->get_result();
$admin_data = $admin_result->fetch_assoc();
$stmt->close();

// Função para listar todos os usuários
$sql_users = "SELECT id, nome, email, tipo_utilizador_id FROM utilizadores";
$result_users = $conn->query($sql_users);

// Função para listar todas as viagens
$sql_viagens = "SELECT id, origem, destino, data_viagem, hora_partida, preco FROM viagens";
$result_viagens = $conn->query($sql_viagens);

// Função para listar transações
$sql_transacoes = "SELECT t.id, u.nome AS cliente, t.valor, t.data_transacao 
                   FROM transacoes t 
                   JOIN utilizadores u ON t.utilizador_id = u.id";
$result_transacoes = $conn->query($sql_transacoes);

// Adicionar nova viagem
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_viagem'])) {
    $origem = $_POST['origem'];
    $destino = $_POST['destino'];
    $data_viagem = $_POST['data_viagem'];
    $hora_partida = $_POST['hora_partida'];
    $preco = $_POST['preco'];

    $sql_add_viagem = "INSERT INTO viagens (origem, destino, data_viagem, hora_partida, preco) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql_add_viagem);
    $stmt->bind_param("ssssd", $origem, $destino, $data_viagem, $hora_partida, $preco);

    if ($stmt->execute()) {
        echo "<script>alert('Viagem adicionada com sucesso!');</script>";
        echo "<script>window.location.href='paginaAdmin.php';</script>";
    } else {
        echo "<script>alert('Erro ao adicionar viagem: " . $conn->error . "');</script>";
    }
    $stmt->close();
}

// Fecha a conexão com a base de dados
$conn->close();
?>
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
    </style>
</head>
<body>
    <div class="container">
        <!-- Barra lateral -->
        <div class="sidebar">
            <div class="logo">Painel Admin</div>
            <nav>
                <div class="nav-item"><a href="#">Usuários</a></div>
                <div class="nav-item"><a href="#">Viagens</a></div>
                <div class="nav-item"><a href="#">Transações</a></div>
                <div class="nav-item"><a href="index.php">Página Principal</a></div>
                <div class="nav-item"><a href="Login.php">Sair</a></div>
            </nav>
        </div>

        <!-- Conteúdo principal -->
        <div class="main-content">
            <header class="header">
                <h1>Bem-vindo, <?= htmlspecialchars($admin_data['nome']); ?></h1>
            </header>

            <!-- Gestão de Usuários -->
            <div class="table-section">
                <h2>Gestão de Usuários</h2>
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
                        <?php while ($row = $result_users->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['id']); ?></td>
                                <td><?= htmlspecialchars($row['nome']); ?></td>
                                <td><?= htmlspecialchars($row['email']); ?></td>
                                <td><?= $row['tipo_utilizador_id'] == 1 ? 'Cliente' : 'Funcionário'; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <!-- Gestão de Viagens -->
            <div class="table-section">
                <h2>Gestão de Viagens</h2>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Origem</th>
                            <th>Destino</th>
                            <th>Data</th>
                            <th>Hora</th>
                            <th>Preço</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result_viagens->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['id']); ?></td>
                                <td><?= htmlspecialchars($row['origem']); ?></td>
                                <td><?= htmlspecialchars($row['destino']); ?></td>
                                <td><?= htmlspecialchars($row['data_viagem']); ?></td>
                                <td><?= htmlspecialchars($row['hora_partida']); ?></td>
                                <td>€<?= number_format($row['preco'], 2, ',', '.'); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <!-- Gestão de Transações -->
            <div class="table-section">
                <h2>Gestão de Transações</h2>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Valor</th>
                            <th>Data</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result_transacoes->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['id']); ?></td>
                                <td><?= htmlspecialchars($row['cliente']); ?></td>
                                <td>€<?= number_format($row['valor'], 2, ',', '.'); ?></td>
                                <td><?= htmlspecialchars($row['data_transacao']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <!-- Adicionar Viagem -->
            <div class="add-viagem">
                <h2>Adicionar Nova Viagem</h2>
                <form action="paginaAdmin.php" method="POST">
                    <input type="text" name="origem" placeholder="Origem" required>
                    <input type="text" name="destino" placeholder="Destino" required>
                    <input type="date" name="data_viagem" required>
                    <input type="time" name="hora_partida" required>
                    <input type="number" step="0.01" name="preco" placeholder="Preço" required>
                    <button type="submit" name="add_viagem">Adicionar</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
