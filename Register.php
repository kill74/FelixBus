<?php
// Configurações do banco de dados
define('DB_HOST', '127.0.0.1');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'trabalho_php');

// Criação da ligação ao banco de dados
$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// Verificar se a ligação foi bem-sucedida
if ($conn->connect_error) {
    die("Erro de ligação ao banco de dados: " . $conn->connect_error);
}

// Definir a codificação de caracteres para evitar problemas com caracteres especiais
$conn->set_charset("utf8mb4");

// Verificar se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Obter os dados do formulário
    $nome = trim($_POST["UserName"]);
    $email = trim($_POST["email"]);
    $senha = trim($_POST["senha"]);
    $senha_hashed = password_hash($senha, PASSWORD_DEFAULT); // Criptografar a senha
    $tipo = 1; // Tipo de utilizador 'cliente'
    $estado = 'ativo'; // Definir o estado do utilizador como 'ativo'

    // Verificar se o email já está registado no banco de dados
    $stmt = $conn->prepare("SELECT id FROM utilizadores WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    // Se o email já existir, mostrar mensagem de erro
    if ($stmt->num_rows > 0) {
        $erro = "Este email já está registado.";
    } else {
        // Preparar a consulta para inserir um novo utilizador
        $stmt = $conn->prepare("INSERT INTO utilizadores (nome, email, palavra_passe, tipo_utilizador_id, estado) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssis", $nome, $email, $senha_hashed, $tipo, $estado);

        // Executar a consulta de inserção
        if ($stmt->execute()) {
            // Se a inserção for bem-sucedida, redirecionar para a página de login
            header("Location: Login.php");
            exit();
        } else {
            // Caso ocorra um erro na inserção, mostrar a mensagem de erro
            $erro = "Erro ao registar. Por favor, tente novamente. Erro: " . $stmt->error;
        }
    }

    // Fechar a consulta após a execução
    $stmt->close();
}

// Fechar a ligação ao banco de dados
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registar</title>
    <style>
        /* Estilos para a página de registo */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
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
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        h2 {
            margin-bottom: 20px;
        }
        input {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border: none;
            border-radius: 5px;
        }
        button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: none;
            background: #1e90ff;
            color: white;
            font-size: 16px;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease;
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
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Registar</h2>
        <!-- Formulário de registo -->
        <form action="Register.php" method="POST">
            <input type="text" name="UserName" placeholder="Nome" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="senha" placeholder="Senha" required>
            <button type="submit">Registrar</button>
        </form>
        <div class="redirect-login">
            <p>Já possui uma conta? <a href="Login.php">Faça login aqui</a></p>
        </div>
    </div>

    <?php
    // Mostrar mensagem de erro, se existir
    if (!empty($erro)) {
        echo "<p style='color: red;'>$erro</p>";
    }
    ?>
</body>
</html>
