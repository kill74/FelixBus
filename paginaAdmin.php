<?php
// Inicia uma sessão para gerir a autenticação do utilizador
session_start();

// Estabelece ligação com a base de dados
require_once 'db_connection.php';

// Verifica se o utilizador está autenticado e se tem permissões de administrador
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'administrador') {
    // Caso o utilizador não tenha permissão, é redirecionado para a página de login com uma mensagem
    echo "<script>alert('Acesso negado. Faça login como administrador.');</script>";
    echo "<script>window.location.href='Login.php';</script>";
    exit; // Termina o processamento do script
}

// Processa o formulário de login enviado pelo utilizador
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtém os dados do formulário: nome e palavra-passe
    $nome = trim($_POST["nome"]);
    $password = trim($_POST["password"]);

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
        $_SESSION["user_role"] = "administrador";
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
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrativo</title>
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
        .sidebar nav {
            display: flex;
            flex-direction: column;
        }
        .nav-item {
            margin: 10px 0;
        }
        .nav-item a {
            color: white;
            text-decoration: none;
            padding: 10px;
            display: block;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .nav-item a:hover {
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
    <!-- Menu lateral com as opções do painel -->
    <div class="sidebar">
        <div class="logo">AdminPanel</div>
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

                    // Verifica se existem rotas para exibir
                    if (!empty($rotas)) {
                        // Itera sobre cada rota e cria uma linha na tabela
                        foreach ($rotas as $rota) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($rota['id']) . "</td>";
                            echo "<td>" . htmlspecialchars($rota['origem']) . "</td>";
                            echo "<td>" . htmlspecialchars($rota['destino']) . "</td>";
                            echo "<td>" . htmlspecialchars($rota['horario']) . "</td>";
                            echo "<td>" . htmlspecialchars($rota['capacidade']) . "</td>";
                            echo "<td>";
                            echo "<a href='editar_rota.php?id=" . urlencode($rota['id']) . "'>Editar</a> | ";
                            echo "<a href='apagar_rota.php?id=" . urlencode($rota['id']) . "' onclick='return confirm(\"Tem a certeza que deseja apagar esta rota?\");'>Apagar</a>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>Nenhuma rota encontrada.</td></tr>";
                    }
?>
                </tbody>
            </table>
        </main>
    </div>
</div>
</body>
</html>
