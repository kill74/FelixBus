<?php
/* Ativar a exibição de erros
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/

// Iniciar a sessão
session_start();

// Ligação à base de dados
$conn = new mysqli('127.0.0.1', 'root', '', 'trabalho_php');
if ($conn->connect_error) {
    die("Erro de ligação à base de dados: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

// Verificar se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Receber os dados do formulário
    $nome = trim($_POST["UserName"]);
    $senha = trim($_POST["senha"]);
    $senha_hashed = password_hash($senha, PASSWORD_DEFAULT);
    $tipo = 1; // Tipo de utilizador (ex: 1 = normal)
    $estado = 'ativo'; // Estado do utilizador

    // Verificar se o nome de utilizador já existe
    $stmt = $conn->prepare("SELECT id FROM utilizadores WHERE nome = ?");
    if (!$stmt) {
        die("Erro na preparação da consulta: " . $conn->error);
    }
    $stmt->bind_param("s", $nome);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Nome de utilizador já existe
        $_SESSION['erro'] = "Erro: Este nome de utilizador já está registado.";
    } else {
        // Inserir o novo utilizador na base de dados
        $stmt = $conn->prepare("INSERT INTO utilizadores (nome, palavra_passe, tipo_utilizador_id, estado) VALUES (?, ?, ?, ?)");
        if (!$stmt) {
            die("Erro na preparação da inserção: " . $conn->error);
        }
        $stmt->bind_param("ssis", $nome, $senha_hashed, $tipo, $estado);

        if ($stmt->execute()) {
            // Registro bem-sucedido
            $_SESSION['sucesso'] = "Utilizador registado com sucesso!";
            header("Location: Login.php"); // Redirecionar para a página de login
            exit();
        } else {
            // Erro ao registar
            $_SESSION['erro'] = "Erro ao registar: " . $stmt->error;
        }
    }

    $stmt->close(); // Fechar a consulta
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registar</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #1e90ff, #00bfff);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #fff;
        }
        .form-container {
            background: rgba(0, 0, 0, 0.7);
            padding: 20px;
            border-radius: 10px;
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        input, button {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border: none;
            border-radius: 5px;
        }
        button {
            background: #1e90ff;
            color: white;
            cursor: pointer;
        }
        button:hover {
            background: #104e8b;
        }
        .redirect-login a {
            color: #1e90ff;
            text-decoration: none;
        }
        .redirect-login a:hover {
            text-decoration: underline;
        }
        .mensagem-erro {
            color: #ff4444;
            margin-bottom: 10px;
        }
        .mensagem-sucesso {
            color: #00C851;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Registar</h2>

        <!-- Exibir mensagens de erro ou sucesso -->
        <?php
        if (isset($_SESSION['erro'])) {
            echo '<div class="mensagem-erro">' . $_SESSION['erro'] . '</div>';
            unset($_SESSION['erro']); // Limpar a mensagem após exibir
        }
        if (isset($_SESSION['sucesso'])) {
            echo '<div class="mensagem-sucesso">' . $_SESSION['sucesso'] . '</div>';
            unset($_SESSION['sucesso']); // Limpar a mensagem após exibir
        }
        ?>

        <form action="" method="POST">
            <input type="text" name="UserName" placeholder="Nome" required>
            <input type="password" name="senha" placeholder="Senha" required>
            <button type="submit">Registar</button>
        </form>
        <div class="redirect-login">
            <p>Já tem uma conta? <a href="Login.php">Faça login aqui</a></p>
        </div>
    </div>
</body>
</html>

<?php
// Fechar a ligação à base de dados no final do script
$conn->close();
?>