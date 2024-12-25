<?php
session_start();
require_once 'db_connection.php'; // Inclui o ficheiro de conexão à base de dados


// Verifica se o utilizador está autenticado e se tem permissões de funcionário ou administrador
if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] != 'funcionario' && $_SESSION['user_role'] != 'admin')) {
    // Caso o utilizador não tenha permissão, é redirecionado para a página de login com uma mensagem
    echo "<script>alert('Acesso negado. Faça login como funcionário ou administrador.');</script>";
    echo "<script>window.location.href='Login.php';</script>";
    exit; // Termina o processamento do script
}

$user_id = $_SESSION['user_id']; // Obtém o ID do utilizador autenticado

// Recupera os dados pessoais do funcionário ou administrador para exibir no painel
$sql_user = "SELECT nome FROM utilizadores WHERE id = ?";
$stmt = $conn->prepare($sql_user);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_result = $stmt->get_result();
$user_data = $user_result->fetch_assoc(); // Armazena os dados do utilizador autenticado
$stmt->close(); // Fecha o statement

// Verifica se o utilizador autenticado é um funcionário ou administrador
if ($user && password_verify($password, $user["password"]) && $user["role"] === "funcionario") {
    // Define as variáveis de sessão para o utilizador autenticado
    $_SESSION["user_id"] = $user['id'];
    $_SESSION["tipo_utilizador"] = $user['tipo_utilizador_id'];
    $_SESSION["nome"] = $user['nome'];
    header("Location: paginaFuncionario.php");
    exit();
} else {
    // Define uma mensagem de erro caso as credenciais sejam inválidas
    $error = "Nome ou palavra-passe inválida.";
}

// Recupera a lista de clientes e os saldos das suas carteiras
$sql_clientes = "SELECT u.id, u.nome, c.saldo FROM utilizadores u 
                JOIN carteira c ON u.id = c.utilizador_id 
                WHERE u.tipo_utilizador_id = 1"; // Tipo 1 corresponde a "cliente"
$result_clientes = $conn->query($sql_clientes); // Executa a query para listar clientes

// Recupera os bilhetes associados aos clientes
$sql_bilhetes = "SELECT b.id, u.nome AS cliente, v.data_viagem, v.hora_partida, b.estado 
                FROM bilhetes b 
                JOIN utilizadores u ON b.utilizador_id = u.id 
                JOIN viagens v ON b.viagem_id = v.id";
$result_bilhetes = $conn->query($sql_bilhetes); // Executa a query para listar bilhetes

// Atualiza o saldo de um cliente
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_saldo'])) {
    $cliente_id = $_POST['cliente_id']; // Obtém o ID do cliente
    $novo_saldo = $_POST['saldo']; // Obtém o novo saldo fornecido pelo funcionário

    // Query para atualizar o saldo na tabela "carteira"
    $sql_update_saldo = "UPDATE carteira SET saldo = ? WHERE utilizador_id = ?";
    $stmt = $conn->prepare($sql_update_saldo);
    $stmt->bind_param("di", $novo_saldo, $cliente_id); // "d" para decimal, "i" para inteiro

    // Executa a atualização e verifica o resultado
    if ($stmt->execute()) {
        echo "<script>alert('Saldo atualizado com sucesso!');</script>";
        echo "<script>window.location.href='painel_funcionario.php';</script>";
    } else {
        echo "<script>alert('Erro ao atualizar saldo: " . $conn->error . "');</script>";
    }
    $stmt->close(); // Fecha o statement
}

$conn->close(); // Fecha a conexão com a base de dados
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Painel do Funcionário</title>
    <link rel="stylesheet" href="style/styleFuncionario.css"> <!-- Inclui o ficheiro CSS para estilo -->
</head>
<body>
    <div class="container">
        <!-- Barra lateral com navegação -->
        <div class="sidebar">
            <div class="logo">Portal Funcionário</div>
            <nav>
                <div class="nav-item">Dashboard</div>
                <div class="nav-item">Clientes</div>
                <div class="nav-item">Bilhetes</div>
                <div class="nav-item">Editar Perfil</div>
                <div class="nav-item">Página Principal</div>
            </nav>
        </div>

        <!-- Conteúdo principal -->
        <div class="main-content">
            <header class="header">
                <!-- Mostra o nome do utilizador autenticado -->
                <div class="user-info">
                    <span class="role-badge">Funcionário</span>
                    <strong>Bem-vindo, <?= htmlspecialchars($user_data['nome']); ?></strong>
                </div>
            </header>

            <main class="dashboard">
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
                                <!--Sistema para ir buscar o nome do cliente/saldo do cliente-->
                            <?php while ($row = $result_clientes->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['nome']); ?></td> <!-- Nome do cliente -->
                                    <td>€<?= number_format($row['saldo'], 2, ',', '.'); ?></td> <!-- Saldo formatado -->
                                    <td>
                                        <!-- Formulário para atualizar o saldo -->
                                        <form action="painel_funcionario.php" method="POST" style="display:inline;">
                                            <input type="hidden" name="cliente_id" value="<?= $row['id']; ?>"> <!-- ID do cliente -->
                                            <input type="number" name="saldo" step="0.01" placeholder="Novo Saldo" required> <!-- Novo saldo -->
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
                                    <td><?= htmlspecialchars($row['cliente']); ?></td> <!-- Nome do cliente -->
                                    <td><?= htmlspecialchars($row['data_viagem']); ?></td> <!-- Data da viagem -->
                                    <td><?= htmlspecialchars($row['hora_partida']); ?></td> <!-- Hora de partida -->
                                    <td><?= htmlspecialchars($row['estado']); ?></td> <!-- Estado do bilhete -->
                                    <td><a href="#" class="action-link">Editar</a></td> <!-- Ação para editar -->
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Dados Pessoais -->
                <div class="profile-section">
                    <h2>Dados Pessoais</h2>
                    <!-- Exibe os dados do utilizador autenticado -->
                    <p><strong>Nome:</strong> <?= htmlspecialchars($user_data['nome']); ?></p>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
