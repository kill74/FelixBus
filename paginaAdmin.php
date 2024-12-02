<?php
// Inicia uma sessão para gerir autenticação
session_start(); 

// Verifica se o utilizador está autenticado; caso contrário, redireciona-o para a página de login
if (!isset($_SESSION['user_id'])) {
    header("Location: Login.php");
    exit();
}

// Estabelece ligação com a base de dados
require_once '<PHP>db_connection.php'; 

// Processa o formulário de login enviado pelo utilizador
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Pesquisa o utilizador na base de dados
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Verifica se as credenciais são do administrador
    if ($user && $user["email"] === "admin@email.com" && $user["password"] === "admin" && $user["role"] === "admin") {
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["role"] = "admin";
        header("Location: paginaAdmin.php");
        exit();
    } else {
        $error = "Email ou palavra-passe inválida.";
    }
}

// Garante que apenas administradores acedem a esta página
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    header("Location: perfil.php");
    exit();
}

// Funções para gestão de dados na base de dados
function listarRotas($conn) {
    // Retorna todas as rotas registadas
    $sql = "SELECT * FROM rotas";
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

function listarUtilizadores($conn) {
    // Retorna todos os utilizadores registados
    $sql = "SELECT * FROM users";
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

function listarAlertas($conn) {
    // Retorna todos os alertas registados
    $sql = "SELECT * FROM alertas";
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

function atualizarDadosPessoais($conn, $userId, $dados) {
    // Atualiza os dados pessoais do utilizador
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
    <!-- Menu lateral com opções do painel -->
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

    <!-- Conteúdo principal -->
    <div class="main-content">
        <header class="header">
            <h1>Bem-vindo, Administrador</h1>
        </header>

        <main class="dashboard">
            <h2>Gestão de Rotas</h2>
            <!-- Tabela com as rotas registadas -->
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
                    // Listar rotas da base de dados
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
