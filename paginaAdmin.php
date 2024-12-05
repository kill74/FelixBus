<?php
// Inicia uma sessão para gerir a autenticação do utilizador
session_start();

// Verifica se o utilizador está autenticado; caso contrário, redireciona-o para a página de login
if (!isset($_SESSION['user_id'])) {
    header("Location: Login.php"); // Redireciona para a página de login
    exit();
}

// Verifica se o utilizador tem a role de administrador
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    header("Location: Login.php"); // Redireciona para a página de login caso não seja administrador
    exit();
}

// Estabelece ligação com a base de dados
require_once '<PHP>db_connection.php'; // Inclui o ficheiro para a conexão com a base de dados

// Processa o formulário de login enviado pelo utilizador
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtém os dados do formulário: nome e palavra-passe
    $nome = $_POST["nome"];
    $password = $_POST["password"];

    // Pesquisa o utilizador na base de dados pelo nome
    $sql = "SELECT * FROM users WHERE nome = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $nome); // Substitui o marcador pela variável $nome
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Verifica se as credenciais são válidas e se o utilizador é administrador
    if ($user && password_verify($password, $user["password"]) && $user["role"] === "admin") {
        // Define as variáveis de sessão para o utilizador autenticado
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["role"] = "admin";
        header("Location: paginaAdmin.php"); // Redireciona para o painel do administrador
        exit();
    } else {
        // Define uma mensagem de erro caso as credenciais sejam inválidas
        $error = "Nome ou palavra-passe inválida.";
    }
}

// Função para listar todas as rotas disponíveis na base de dados
function listarRotas($conn) {
    $sql = "SELECT * FROM rotas"; // Consulta todas as rotas
    $result = $conn->query($sql); // Executa a consulta
    return $result->fetch_all(MYSQLI_ASSOC); // Retorna as rotas como um array associativo
}

// Função para listar todos os utilizadores registados
function listarUtilizadores($conn) {
    $sql = "SELECT * FROM users"; // Consulta todos os utilizadores
    $result = $conn->query($sql); // Executa a consulta
    return $result->fetch_all(MYSQLI_ASSOC); // Retorna os utilizadores como um array associativo
}

// Função para listar todos os alertas registados
function listarAlertas($conn) {
    $sql = "SELECT * FROM alertas"; // Consulta todos os alertas
    $result = $conn->query($sql); // Executa a consulta
    return $result->fetch_all(MYSQLI_ASSOC); // Retorna os alertas como um array associativo
}

// Função para atualizar os dados pessoais do utilizador
function atualizarDadosPessoais($conn, $userId, $dados) {
    $sql = "UPDATE users SET nome = ?, email = ? WHERE id = ?"; // Query de atualização
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $dados['nome'], $dados['email'], $userId); // Liga os parâmetros à query
    $stmt->execute(); // Executa a atualização
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrativo</title>
    <link rel="stylesheet" href="admin_styles.css"> <!-- Liga o CSS para estilos -->
</head>
<body>
<div class="container">
    <!-- Menu lateral com as opções do painel -->
    <div class="sidebar">
        <div class="logo">AdminPanel</div> <!-- Logotipo do painel -->
        <nav>
            <!-- Links para diferentes secções do painel administrativo -->
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
            <h1>Bem-vindo, Administrador</h1> <!-- Mensagem de boas-vindas -->
        </header>

        <main class="dashboard">
            <h2>Gestão de Rotas</h2>
            <!-- Tabela para listar as rotas registadas -->
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
                    // Obtém as rotas da base de dados
                    $rotas = listarRotas($conn);
                    foreach ($rotas as $rota) {
                        // Exibe cada rota numa linha da tabela
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
