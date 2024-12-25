<?php
// Inicia a sessão para verificar autenticação e permissões
session_start();
require_once 'db_connection.php'; // Inclui o ficheiro de conexão à base de dados

// Verifica se o utilizador está autenticado como funcionário ou administrador
if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] != 'funcionario' && $_SESSION['user_role'] != 'admin')) {
    echo "<script>alert('Acesso negado. Faça login como funcionário ou administrador.');</script>";
    echo "<script>window.location.href='Login.php';</script>";
    exit;
}

// Obtém o ID do utilizador autenticado
$user_id = $_SESSION['user_id'];

// Recupera os dados pessoais do funcionário para exibir no painel
$sql_user = "SELECT nome FROM utilizadores WHERE id = ?";
$stmt = $conn->prepare($sql_user);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_result = $stmt->get_result();
$user_data = $user_result->fetch_assoc();
$stmt->close();

// Recupera a lista de clientes e os saldos das suas carteiras
$sql_clientes = "SELECT u.id, u.nome, c.saldo FROM utilizadores u 
                 JOIN carteira c ON u.id = c.utilizador_id 
                 WHERE u.tipo_utilizador_id = 1"; // Tipo 1 corresponde a "cliente"
$result_clientes = $conn->query($sql_clientes);

// Recupera os bilhetes associados aos clientes
$sql_bilhetes = "SELECT b.id, u.nome AS cliente, v.data_viagem, v.hora_partida, b.estado 
                 FROM bilhetes b 
                 JOIN utilizadores u ON b.utilizador_id = u.id 
                 JOIN viagens v ON b.viagem_id = v.id";
$result_bilhetes = $conn->query($sql_bilhetes);

// Atualiza o saldo de um cliente
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_saldo'])) {
    $cliente_id = $_POST['cliente_id']; // Obtém o ID do cliente
    $novo_saldo = $_POST['saldo']; // Obtém o novo saldo fornecido pelo funcionário

    // Atualiza o saldo na tabela "carteira"
    $sql_update_saldo = "UPDATE carteira SET saldo = ? WHERE utilizador_id = ?";
    $stmt = $conn->prepare($sql_update_saldo);
    $stmt->bind_param("di", $novo_saldo, $cliente_id);

    if ($stmt->execute()) {
        echo "<script>alert('Saldo atualizado com sucesso!');</script>";
        echo "<script>window.location.href='paginaFuncionario.php';</script>";
    } else {
        echo "<script>alert('Erro ao atualizar saldo: " . $conn->error . "');</script>";
    }
    $stmt->close();
}

$conn->close(); // Fecha a conexão com a base de dados
?>
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
                <div class="nav-item"><a href="#">Editar Perfil</a></div>
                <div class="nav-item"><a href="#">Página Principal</a></div>
            </nav>
        </div>

        <!-- Conteúdo principal -->
        <div class="main-content">
            <header class="header">
                <h1>Bem-vindo, <?= htmlspecialchars($user_data['nome']); ?></h1>
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
                        <?php while ($row = $result_clientes->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['nome']); ?></td>
                                <td>€<?= number_format($row['saldo'], 2, ',', '.'); ?></td>
                                <td>
                                    <form action="paginaFuncionario.php" method="POST" style="display:inline;">
                                        <input type="hidden" name="cliente_id" value="<?= $row['id']; ?>">
                                        <input type="number" name="saldo" step="0.01" placeholder="Novo Saldo" required>
                                        <button type="submit" name="update_saldo">Atualizar</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
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
                        <?php while ($row = $result_bilhetes->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['cliente']); ?></td>
                                <td><?= htmlspecialchars($row['data_viagem']); ?></td>
                                <td><?= htmlspecialchars($row['hora_partida']); ?></td>
                                <td><?= htmlspecialchars($row['estado']); ?></td>
                                <td><a href="#" class="action-link">Editar</a></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
