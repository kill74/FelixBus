<?php
session_start(); 

// Verificar se o utilizador está autenticado
if (!isset($_SESSION['user_id'])) {
    // Redirecionar para a página de login se o utilizador não estiver autenticado
    header("Location: PaginaLogin.php");
    exit();
}

// Incluir a ligação à base de dados
require_once 'db_connection.php'; // fazer ligação a base de dados 

// Verificar o método do pedido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Consultar o utilizador na base de dados
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && $user["email"] === "admin@email.com" && $user["password"] === "admin" && $user["role"] === "admin") {
        // Guardar os dados do administrador na sessão
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["role"] = "admin";
        header("Location: paginaAdmin.php");
        exit();
    } else {
        // Caso as credenciais sejam inválidas
        $error = "Email ou palavra-passe inválida.";
    }
}

// Verificar se o utilizador é administrador
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    // Redirecionar para o dashboard do utilizador normal
    header("Location: perfil.php");
    exit();
}

// Funções de gestão
function listarRotas($conn) {
    // Listar todas as rotas na base de dados
    $sql = "SELECT * FROM rotas";
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

function listarUtilizadores($conn) {
    // Listar todos os utilizadores
    $sql = "SELECT * FROM users";
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

function listarAlertas($conn) {
    // Listar todos os alertas/informações/promoções
    $sql = "SELECT * FROM alertas";
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

function atualizarDadosPessoais($conn, $userId, $dados) {
    // Atualizar dados pessoais do utilizador
    $sql = "UPDATE users SET nome = ?, email = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $dados['nome'], $dados['email'], $userId);
    $stmt->execute();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrativo</title>
    <link rel="stylesheet" href="admin_styles.css">
</head>
<body>
<div class="container">
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">AdminPanel</div>
        <nav>
            <div class="nav-item"><a href="admin_dashboard.php">Dashboard</a></div>
            <div class="nav-item"><a href="gestao_rotas.php">Gestão de Rotas</a></div>
            <div class="nav-item"><a href="gestao_utilizadores.php">Gestão de Utilizadores</a></div>
            <div class="nav-item"><a href="gestao_alertas.php">Gestão de Alertas</a></div>
            <div class="nav-item"><a href="perfil.php">Os Meus Dados</a></div>
            <div class="nav-item"><a href="logout.php">Terminar Sessão</a></div>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <header class="header">
            <h1>Bem-vindo, Administrador</h1>
        </header>

        <main class="dashboard">
            <h2>Gestão de Rotas</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Origem</th>
                        <th>Destino</th>
                        <th>Horário</th>
                        <th>Capacidade</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $rotas = listarRotas($conn);
                    foreach ($rotas as $rota) {
                        echo "<tr>
                                <td>{$rota['id']}</td>
                                <td>{$rota['origem']}</td>
                                <td>{$rota['destino']}</td>
                                <td>{$rota['horario']}</td>
                                <td>{$rota['capacidade']}</td>
                                <td><a href='editar_rota.php?id={$rota['id']}'>Editar</a></td>
                              </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </main>
    </div>
</div>
</body>
</html>
